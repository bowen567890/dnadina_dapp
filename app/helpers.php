<?php

use App\Services\AppService;
use App\Services\LangService;
use Carbon\Carbon;
use App\Models\NodeConfig;
use App\Models\RankConfig;

function getImageUrl($path)
{
    $host =  config('env.APP_URL').'/uploads/';
    //     $host =  env('OSS_URL');
    return $host.$path;
}

function Lang($slug, array $params = [], $local = null)
{
    //空判断
    if (empty($slug)) {
        return null;
    }
    //获取当前语言
    //fix:语言设计非常占内存需要优化
    $local = AppService::getService()->local($local);
    $langContent = LangService::getService()->getV2Lang($local, $slug);
    if (empty($langContent)) {
        LangService::getService()->createLang($slug, $params);
    }
    $langContent = $langContent ?? $slug;
    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            $langContent = str_replace("{" . $key . "}", $value, $langContent);
        }
    }
    return $langContent ?? $slug;
}

function mgLang($slug)
{
  return LangService::getService()->getMLang($slug);
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }
        
        if (is_array($key)) {
            return app('config')->set($key);
        }
        
        return app('config')->get($key, $default);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->configPath($path);
    }
}


function Setting(string $slug, $default = null)
{
    return \App\Services\Common\ConfigService::getService()->getConfig($slug, $default);
}


function SettingByDatabase(string $slug, $default = null)
{

    return \App\Services\Common\ConfigService::getService()->getConfigByDatabase($slug, $default);

}

function SettingBool(string $slug, $default = null)
{
    return (bool)\App\Services\Common\ConfigService::getService()->getConfig($slug, $default);
}

function SettingLocal(string $slug, $default = null, $local = null)
{

    $l = AppService::getService()->local($local);

    return data_get(\App\Services\Common\ConfigService::getService()->getConfig($slug, $default), $l);
}

function LocalDataGet($data, $local = null)
{
    $local = AppService::getService()->local($local);
    return data_get($data, $local);
}

function ImageUrl($path, $disk = null)
{
    if (empty($path)) return $path;
    if (Str::contains($path, '//')) {
        return $path;
    }
    
    // 如果指定了磁盘，使用该磁盘生成URL
    if ($disk) {
        try {
            return Storage::disk($disk)->url($path);
        } catch (Exception $e) {
            \Log::error('生成文件URL失败: ' . $e->getMessage());
        }
    }
    
    // 默认逻辑
    $storageUrl = Storage::disk($disk)->url($path);
    $parsedUrl = parse_url($storageUrl);

    // 使用当前请求的域名拼接
    $scheme = request()->getScheme();
    $host = request()->getHost();
    return $scheme . '://' . $host . ($parsedUrl['path'] ?? '');
}

function TimeFormat($time)
{
    return $time ? Carbon::make($time)->format("Y-m-d H:i:s") : null;
}

function MoneyFormat($money)
{
    return sprintf('%.'.Setting('money_decimal').'f',$money);
}

function Money4Format($money)
{
    return sprintf('%.4f',$money);
}

function UsdtToBalance($v): float
{
    return $v * Setting('usdt_money_rate');
}


function FbToRmb($v)
{
    $vv = $v / Setting('rmb_money_rate');
    return number_format($vv, $vv > 100 ? 0 : 2);
}



/**
 *
 * 金额格式化
 *
 * @return void
 *
 */
function ShowMoney($value, $isUsdt = false)
{
    $f_value = (float)$value;

    if ($f_value === 0) return "-";

    if ($isUsdt) $f_value = UsdtToBalance($value);

    $fiat_code = Setting('fiat_code');

    //$money = number2chinese(round($f_value));
    $html = "";
    $html .= $isUsdt ? "<div class='text-bold'>" . (float)$value . " </div>" : "";
  //  $html .= "<div class='text-bold'>" . round($f_value, 4) . "<span class='margin-left-xs'>$fiat_code</span></div>";
    //$html .= $f_value >= 1000 ? "<div> $money </div>" : "";
  //  $html .= $f_value > 0 ? "<div>≈ " . FbToRmb($f_value) . "元</div>" : "";
    return $html;
}



/**
 *
 * 人民币格式化
 *
 * @return void
 *
 */
function ShowRmb($value, $isUsdt = false)
{
    $f_value = (float)$value;
    if ($f_value === 0) return "-";
    if ($isUsdt) $f_value = UsdtToBalance($value);
    return $f_value > 0 ? "≈ " . FbToRmb($f_value) . "元" : "";
}


/**
 *
 * 金额类型改变
 *
 * @return void
 *
 */
function MoneyShow($v)
{
    return (float)$v;
}



/**
 *
 * 手机号码隐藏
 *
 * @return void
 *
 */
function ShowPhoneNumber($str)
{
    $str=$str;
    $resstr=substr_replace($str,'****',3,4);
    return $resstr;
}


/**
 * 获取父级ID集
 *
 * @return void
 *
 */
function getParentIds(string $path)
{

    $ids = explode('/',$path);
    unset($ids[0]);
    unset($ids[1]);
    $ids = array_reverse($ids);
    unset($ids[0]);
    if (count($ids)>3) {
        return [
            1=>$ids[1],
            2=>$ids[2],
            3=>$ids[3]
        ];
    }
    return $ids;
}



/**
 * 根据给定的数字返回“участник”的正确形式。
 *
 * @param int $number 要处理的数字。
 * @return string 返回正确形式的“участник”。
 */
function getUchastnikForm(int $number): string {
    // 取绝对值，以处理负数的情况
    $number = abs($number);

    // 获取最后两位数字，以处理11-14的特殊情况
    $lastTwoDigits = $number % 100;

    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
        return 'участников';
    }

    // 获取最后一位数字
    $lastDigit = $number % 10;

    switch ($lastDigit) {
        case 1:
            return 'участник';
        case 2:
        case 3:
        case 4:
            return 'участника';
        default:
            return 'участников';
    }
}


/**
 * 判断地址是否是合法的BSC地址
 * @param string $address
 * @return bool
 */
function isValidBscAddress($address)
{
    // 正则表达式检查是否以 0x 开头，后面跟着 40 个十六进制字符
    return preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
}


/**
 * 判断地址是否是合法的波场地址
 * @param string $address
 * @return bool
 */
function isValidTronAddress($address)
{
    // 正则表达式检查是否以 T 开头，后面是 33 个 Base58 字符
    return preg_match('/^T[a-zA-Z1-9]{33}$/', $address);
}



function HideAddress($address)
{
    if (empty($address) || strlen($address) <= 6) {
        return $address;
    }
    $visiblePart = substr($address, -6);
    return '****' . $visiblePart;
}


function GetAddressNumber($address){
    $num = 0;
    try {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:9090',
            'timeout'  => 2.0,
        ]);
        $response = $client->post('/v1/eth/balanceOf',[
            'form_params' => [
                'address' => $address,
                'contract_address' => '0x1000000000000000000000000000000000000000'
            ]
        ]);
        $response = json_decode($response->getBody()->getContents(),true);
        if (isset($response['code']) && $response['code'] === 200) {
            $num = $response['data']['balance'];
            $num = bcdiv($num,1000000000000000000,2);
        }
        return $num;
    }catch (Exception $e){
        return $num;
    }
}

//波场地址校验
function checkBnbAddress($address)
{
    if (!is_string($address) || !$address || mb_strlen($address, 'UTF8')!=42) {
        return false;
    }
    
    $first = mb_substr($address, 0, 1, 'UTF8');
    $first2 = mb_substr($address, 1, 1, 'UTF8');
    if ($first!='0') {
        return false;
    }
    if ($first2!='x') {
        return false;
    }
    return true;
}

/**
 * 得到新订单号
 * @return  string
 */
function get_ordernum($prefix='') {
    return $prefix.date('ymdHis') . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
}

function getLang() {
    $txt = request()->header('lang', 'zh_CN');
    $lang = '';
    if (in_array($txt, ['tw','en','th','vi','ko','ja'])) {
        $lang = '_'.$txt;
    }
    return $lang;
}

function getNodeName($lv=0, $NodeConfig=[])
{
    if (!$NodeConfig) {
        $NodeConfig = NodeConfig::GetListCache();
    }
    $NodeConfig = array_column($NodeConfig, null, 'lv');
    
//     $lang = getLang();
//     $nameField = 'name'.$lang;
    //     $descField = 'desc'.$lang;
    $name = isset($NodeConfig[$lv]) ? LocalDataGet($NodeConfig[$lv]['name']) : '';
    
    return $name;
}

function getRankName($lv=0, $RankConfig=[])
{
    if (!$RankConfig) {
        $RankConfig = RankConfig::GetListCache();
    }
    $RankConfig = array_column($RankConfig, null, 'lv');
    
    $lang = getLang();
    $nameField = 'name'.$lang;
    //     $descField = 'desc'.$lang;
    $name = isset($RankConfig[$lv][$nameField]) ? $RankConfig[$lv][$nameField] : '';
    
    return $name;
}

function filterInput($text)
{
    //过滤表情符号
    $emojiPattern = '/[\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{2700}-\x{27BF}]/u'; // Unicode范围内包含所有常见表情符号
    $text = preg_replace($emojiPattern, '', $text);
    $text = strip_tags($text);  //去除html标签
    $text = trim($text);        //去除空格
    return $text;
}

function getRate($rate, $decimal='3', $max = '1', $min='0')
{
    $rate = bccomp($rate, $max, $decimal)>=0 ? $max : $rate;
    $rate = bccomp($rate, $min, $decimal)<=0 ? $min : $rate;
    return $rate;
}


