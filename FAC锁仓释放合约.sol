// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

interface IERC20 {
    function transfer(address to, uint256 amount) external returns (bool);
    function balanceOf(address account) external view returns (uint256);
}

/**
 * @title SafeMath
 * @dev 安全数学运算库，防止溢出
 */
library SafeMath {
    function add(uint256 a, uint256 b) internal pure returns (uint256) {
        uint256 c = a + b;
        require(c >= a, "SafeMath: addition overflow");
        return c;
    }

    function sub(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b <= a, "SafeMath: subtraction overflow");
        uint256 c = a - b;
        return c;
    }

    function mul(uint256 a, uint256 b) internal pure returns (uint256) {
        if (a == 0) {
            return 0;
        }
        uint256 c = a * b;
        require(c / a == b, "SafeMath: multiplication overflow");
        return c;
    }

    function div(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b > 0, "SafeMath: division by zero");
        uint256 c = a / b;
        return c;
    }

    function mod(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b != 0, "SafeMath: modulo by zero");
        return a % b;
    }
}

contract FACVesting {
    using SafeMath for uint256;

    IERC20 public facToken;

    // 接收地址
    address public recipient;

    // 合约管理员
    address public owner;

    // 时间相关
    uint256 public startTime;
    uint256 public constant SECONDS_PER_DAY = 86400;

    // 释放参数 - 使用常量避免计算错误
    uint256 public constant TOTAL_LOCKED = 180000000 * 10**18; // 1.8亿FAC
    uint256 public constant FIRST_YEAR_TOTAL = 18000000 * 10**18; // 第一年1800万FAC
    uint256 public constant DAILY_FIRST_YEAR = 49315068493150684931506; // 精确计算：18000000 * 10^18 / 365
    uint256 public constant REDUCTION_NUMERATOR = 75; // 递减分子
    uint256 public constant REDUCTION_DENOMINATOR = 100; // 递减分母
    uint256 public constant DAYS_PER_YEAR = 365;

    // 状态跟踪
    mapping(uint256 => bool) public dayReleased; // 记录每天是否已释放
    uint256 public totalReleased; // 总释放量
    uint256 public lastReleaseDay; // 最后释放的天数

    // 事件
    event TokensReleased(uint256 day, uint256 year, uint256 amount, address recipient);
    event VestingStarted(uint256 startTime);
    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);

    modifier onlyOwner() {
        require(msg.sender == owner, "Vesting: caller is not the owner");
        _;
    }

    modifier vestingStarted() {
        require(startTime > 0, "Vesting: vesting not started");
        _;
    }

    constructor(
        address _facToken,
        address _recipient
    ) {
        require(_facToken != address(0), "Vesting: FAC token address cannot be zero");
        require(_recipient != address(0), "Vesting: recipient cannot be zero");

        facToken = IERC20(_facToken);
        recipient = _recipient;
        owner = msg.sender;
    }

    // 开始锁仓释放（需要先转入代币）
    function startVesting() external onlyOwner {
        require(startTime == 0, "Vesting: already started");
        require(facToken.balanceOf(address(this)) >= TOTAL_LOCKED, "Vesting: insufficient token balance");

        startTime = block.timestamp;
        emit VestingStarted(startTime);
    }

    // 计算当前是第几天（基于UTC日期，从启动那天算第1天）
    function getCurrentDay() public view vestingStarted returns (uint256) {
        uint256 startDayTimestamp = SafeMath.div(startTime, SECONDS_PER_DAY);
        uint256 currentDayTimestamp = SafeMath.div(block.timestamp, SECONDS_PER_DAY);
        uint256 daysPassed = SafeMath.sub(currentDayTimestamp, startDayTimestamp);
        return SafeMath.add(daysPassed, 1);
    }

    // 安全计算当前是第几年（从1开始）
    function getCurrentYear() public view vestingStarted returns (uint256) {
        uint256 currentDay = getCurrentDay();
        uint256 yearsPassed = SafeMath.div(SafeMath.sub(currentDay, 1), DAYS_PER_YEAR);
        return SafeMath.add(yearsPassed, 1);
    }

    // 安全计算指定年份的每日释放量
    function getDailyAmountForYear(uint256 year) public pure returns (uint256) {
        require(year > 0, "Vesting: year must be greater than 0");
        require(year <= 50, "Vesting: year too large"); // 防止过大的年份导致计算问题

        if (year == 1) {
            return DAILY_FIRST_YEAR;
        }

        uint256 amount = DAILY_FIRST_YEAR;

        // 安全计算递减
        for (uint256 i = 2; i <= year; i++) {
            // 使用安全乘法和除法
            amount = SafeMath.div(
                SafeMath.mul(amount, REDUCTION_NUMERATOR),
                REDUCTION_DENOMINATOR
            );

            // 防止金额变得太小
            if (amount == 0) {
                break;
            }
        }

        return amount;
    }

    // 安全计算到指定年份结束的累计释放量
    function getTotalReleasedByYear(uint256 year) public pure returns (uint256) {
        require(year > 0, "Vesting: year must be greater than 0");
        require(year <= 50, "Vesting: year too large");

        uint256 total = 0;

        for (uint256 i = 1; i <= year; i++) {
            uint256 dailyAmount = getDailyAmountForYear(i);
            if (dailyAmount == 0) {
                break;
            }

            uint256 yearlyAmount = SafeMath.mul(dailyAmount, DAYS_PER_YEAR);
            total = SafeMath.add(total, yearlyAmount);

            // 防止超过总锁仓量
            if (total >= TOTAL_LOCKED) {
                total = TOTAL_LOCKED;
                break;
            }
        }

        return total;
    }

    // 检查是否还有代币可释放
    function hasTokensToRelease() public view returns (bool) {
        return totalReleased < TOTAL_LOCKED;
    }

    // 每日释放代币 - 使用安全数学运算（仅合约管理者）
    function releaseTokens() external onlyOwner vestingStarted {
        require(hasTokensToRelease(), "Vesting: all tokens released");

        uint256 currentDay = getCurrentDay();
        require(!dayReleased[currentDay], "Vesting: tokens already released today");
        require(currentDay > lastReleaseDay, "Vesting: can only release once per day");

        uint256 currentYear = getCurrentYear();
        uint256 dailyAmount = getDailyAmountForYear(currentYear);

        require(dailyAmount > 0, "Vesting: daily amount is zero");

        // 安全检查是否超过总锁仓量
        uint256 newTotalReleased = SafeMath.add(totalReleased, dailyAmount);
        if (newTotalReleased > TOTAL_LOCKED) {
            // 如果超过，只释放剩余部分
            dailyAmount = SafeMath.sub(TOTAL_LOCKED, totalReleased);
            newTotalReleased = TOTAL_LOCKED;
        }

        require(dailyAmount > 0, "Vesting: no tokens to release");

        // 标记今天已释放
        dayReleased[currentDay] = true;
        totalReleased = newTotalReleased;
        lastReleaseDay = currentDay;

        // 转账给接收地址
        require(facToken.transfer(recipient, dailyAmount), "Vesting: transfer failed");

        emit TokensReleased(currentDay, currentYear, dailyAmount, recipient);
    }

    // 查看今天是否已经释放
    function isTodayReleased() external view vestingStarted returns (bool) {
        return dayReleased[getCurrentDay()];
    }

    // 安全计算今天可释放的数量
    function getTodayReleaseAmount() external view vestingStarted returns (uint256) {
        uint256 currentDay = getCurrentDay();

        if (dayReleased[currentDay] || !hasTokensToRelease()) {
            return 0;
        }

        uint256 currentYear = getCurrentYear();
        uint256 dailyAmount = getDailyAmountForYear(currentYear);

        // 安全检查
        uint256 potentialTotal = SafeMath.add(totalReleased, dailyAmount);
        if (potentialTotal > TOTAL_LOCKED) {
            return SafeMath.sub(TOTAL_LOCKED, totalReleased);
        }

        return dailyAmount;
    }

    // 查看合约状态 - 使用安全计算
    function getContractInfo() external view returns (
        uint256 _totalLocked,
        uint256 _totalReleased,
        uint256 _currentDay,
        uint256 _currentYear,
        uint256 _todayAmount,
        bool _todayReleased,
        bool _hasMoreTokens
    ) {
        _totalLocked = TOTAL_LOCKED;
        _totalReleased = totalReleased;
        _hasMoreTokens = hasTokensToRelease();

        if (startTime == 0) {
            return (_totalLocked, 0, 0, 0, 0, false, _hasMoreTokens);
        }

        _currentDay = getCurrentDay();
        _currentYear = getCurrentYear();
        _todayReleased = dayReleased[_currentDay];

        if (_todayReleased || !_hasMoreTokens) {
            _todayAmount = 0;
        } else {
            uint256 dailyAmount = getDailyAmountForYear(_currentYear);
            uint256 potentialTotal = SafeMath.add(totalReleased, dailyAmount);

            if (potentialTotal > TOTAL_LOCKED) {
                _todayAmount = SafeMath.sub(TOTAL_LOCKED, totalReleased);
            } else {
                _todayAmount = dailyAmount;
            }
        }
    }

    // 查看合约中剩余代币数量
    function getRemainingTokens() external view returns (uint256) {
        return facToken.balanceOf(address(this));
    }

    // 更新接收地址（仅所有者）
    function updateRecipient(address newRecipient) external onlyOwner {
        require(newRecipient != address(0), "Vesting: new recipient cannot be zero");
        recipient = newRecipient;
    }

    // 转移所有权
    function transferOwnership(address newOwner) external onlyOwner {
        require(newOwner != address(0), "Vesting: new owner cannot be zero");
        emit OwnershipTransferred(owner, newOwner);
        owner = newOwner;
    }

    // 放弃所有权
    function renounceOwnership() external onlyOwner {
        emit OwnershipTransferred(owner, address(0));
        owner = address(0);
    }
}
