<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection path
     * @property Grid\Column|Collection method
     * @property Grid\Column|Collection ip
     * @property Grid\Column|Collection input
     * @property Grid\Column|Collection deleted_at
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection wx_openid
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection google_two_fa_secret
     * @property Grid\Column|Collection google_two_fa_enable
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection banner_type
     * @property Grid\Column|Collection banner
     * @property Grid\Column|Collection tab
     * @property Grid\Column|Collection key
     * @property Grid\Column|Collection help
     * @property Grid\Column|Collection element
     * @property Grid\Column|Collection rule
     * @property Grid\Column|Collection contract_address
     * @property Grid\Column|Collection decimals
     * @property Grid\Column|Collection img
     * @property Grid\Column|Collection st
     * @property Grid\Column|Collection chain_id
     * @property Grid\Column|Collection currency_id
     * @property Grid\Column|Collection other_id
     * @property Grid\Column|Collection currency_name
     * @property Grid\Column|Collection other_name
     * @property Grid\Column|Collection price
     * @property Grid\Column|Collection back_price
     * @property Grid\Column|Collection is_search
     * @property Grid\Column|Collection last_time
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection group
     * @property Grid\Column|Collection content
     * @property Grid\Column|Collection required
     * @property Grid\Column|Collection color
     * @property Grid\Column|Collection loan_month
     * @property Grid\Column|Collection amount
     * @property Grid\Column|Collection interest_rate
     * @property Grid\Column|Collection interest_amount
     * @property Grid\Column|Collection leverage
     * @property Grid\Column|Collection term_days
     * @property Grid\Column|Collection service_fee
     * @property Grid\Column|Collection loan_time
     * @property Grid\Column|Collection end_time
     * @property Grid\Column|Collection next_time
     * @property Grid\Column|Collection repay_time
     * @property Grid\Column|Collection repay_amount
     * @property Grid\Column|Collection overdue_days
     * @property Grid\Column|Collection overdue_fee
     * @property Grid\Column|Collection ordernum
     * @property Grid\Column|Collection hash
     * @property Grid\Column|Collection remark
     * @property Grid\Column|Collection pay_status
     * @property Grid\Column|Collection loan_id
     * @property Grid\Column|Collection period
     * @property Grid\Column|Collection due_amount
     * @property Grid\Column|Collection btime
     * @property Grid\Column|Collection etime
     * @property Grid\Column|Collection overdue_rate
     * @property Grid\Column|Collection coin_img
     * @property Grid\Column|Collection rate
     * @property Grid\Column|Collection contract_address_lp
     * @property Grid\Column|Collection pancake_cate
     * @property Grid\Column|Collection is_sync
     * @property Grid\Column|Collection is_success
     * @property Grid\Column|Collection precision
     * @property Grid\Column|Collection describe
     * @property Grid\Column|Collection cover
     * @property Grid\Column|Collection is_top
     * @property Grid\Column|Collection read_min
     * @property Grid\Column|Collection fake_read_nums
     * @property Grid\Column|Collection read_nums
     * @property Grid\Column|Collection pushd_at
     * @property Grid\Column|Collection total
     * @property Grid\Column|Collection gift_power
     * @property Grid\Column|Collection withdraw_rate
     * @property Grid\Column|Collection node_id
     * @property Grid\Column|Collection ispop
     * @property Grid\Column|Collection date
     * @property Grid\Column|Collection datetime
     * @property Grid\Column|Collection channel_id
     * @property Grid\Column|Collection default_lang
     * @property Grid\Column|Collection money_decimal
     * @property Grid\Column|Collection usdt_withdraw_enable
     * @property Grid\Column|Collection fac_withdraw_enable
     * @property Grid\Column|Collection usdt_withdraw_rate
     * @property Grid\Column|Collection fac_withdraw_rate
     * @property Grid\Column|Collection usdt_min_withdraw
     * @property Grid\Column|Collection usdt_max_withdraw
     * @property Grid\Column|Collection usdt_daily_max_withdraw
     * @property Grid\Column|Collection fac_min_withdraw
     * @property Grid\Column|Collection fac_max_withdraw
     * @property Grid\Column|Collection fac_daily_max_withdraw
     * @property Grid\Column|Collection lock_address
     * @property Grid\Column|Collection power_price
     * @property Grid\Column|Collection zhi_rate
     * @property Grid\Column|Collection ceng_rate
     * @property Grid\Column|Collection address
     * @property Grid\Column|Collection deep
     * @property Grid\Column|Collection code
     * @property Grid\Column|Collection zhi_num
     * @property Grid\Column|Collection team_num
     * @property Grid\Column|Collection self_yeji
     * @property Grid\Column|Collection team_yeji
     * @property Grid\Column|Collection total_yeji
     * @property Grid\Column|Collection valid_status
     * @property Grid\Column|Collection lock_amount
     * @property Grid\Column|Collection amount_type
     * @property Grid\Column|Collection before
     * @property Grid\Column|Collection after
     * @property Grid\Column|Collection add_type
     * @property Grid\Column|Collection me_power
     * @property Grid\Column|Collection team_power
     * @property Grid\Column|Collection team_valid_count
     * @property Grid\Column|Collection team_level_id
     * @property Grid\Column|Collection rate1
     * @property Grid\Column|Collection rate2
     * @property Grid\Column|Collection withdraw_usdt_status
     * @property Grid\Column|Collection withdraw_nadi_status
     * @property Grid\Column|Collection num
     * @property Grid\Column|Collection total_amount
     * @property Grid\Column|Collection is_settlement
     * @property Grid\Column|Collection total_power
     * @property Grid\Column|Collection valid_power
     * @property Grid\Column|Collection expired_power
     * @property Grid\Column|Collection machine_power
     * @property Grid\Column|Collection node_power
     * @property Grid\Column|Collection power_type
     * @property Grid\Column|Collection power
     * @property Grid\Column|Collection order_no
     * @property Grid\Column|Collection nums
     * @property Grid\Column|Collection other_nums
     * @property Grid\Column|Collection coin
     * @property Grid\Column|Collection other_coin
     * @property Grid\Column|Collection coin_address
     * @property Grid\Column|Collection coin1_address
     * @property Grid\Column|Collection total1_amount
     * @property Grid\Column|Collection extend
     * @property Grid\Column|Collection no
     * @property Grid\Column|Collection coin_id
     * @property Grid\Column|Collection fee
     * @property Grid\Column|Collection fee_amount
     * @property Grid\Column|Collection ac_amount
     * @property Grid\Column|Collection finsh_time
     * @property Grid\Column|Collection is_push
     * @property Grid\Column|Collection register_num
     * @property Grid\Column|Collection backend_recharge_usdt
     * @property Grid\Column|Collection backend_recharge_coin
     * @property Grid\Column|Collection recharge_usdt_num
     * @property Grid\Column|Collection recharge_usdt_count
     * @property Grid\Column|Collection recharge_coin_num
     * @property Grid\Column|Collection recharge_coin_count
     * @property Grid\Column|Collection withdraw_num
     * @property Grid\Column|Collection withdraw_count
     * @property Grid\Column|Collection withdraw_fee
     * @property Grid\Column|Collection power_income
     * @property Grid\Column|Collection equipment_income
     * @property Grid\Column|Collection node_income
     * @property Grid\Column|Collection node_withdraw_income
     *
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection path(string $label = null)
     * @method Grid\Column|Collection method(string $label = null)
     * @method Grid\Column|Collection ip(string $label = null)
     * @method Grid\Column|Collection input(string $label = null)
     * @method Grid\Column|Collection deleted_at(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection wx_openid(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection google_two_fa_secret(string $label = null)
     * @method Grid\Column|Collection google_two_fa_enable(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection banner_type(string $label = null)
     * @method Grid\Column|Collection banner(string $label = null)
     * @method Grid\Column|Collection tab(string $label = null)
     * @method Grid\Column|Collection key(string $label = null)
     * @method Grid\Column|Collection help(string $label = null)
     * @method Grid\Column|Collection element(string $label = null)
     * @method Grid\Column|Collection rule(string $label = null)
     * @method Grid\Column|Collection contract_address(string $label = null)
     * @method Grid\Column|Collection decimals(string $label = null)
     * @method Grid\Column|Collection img(string $label = null)
     * @method Grid\Column|Collection st(string $label = null)
     * @method Grid\Column|Collection chain_id(string $label = null)
     * @method Grid\Column|Collection currency_id(string $label = null)
     * @method Grid\Column|Collection other_id(string $label = null)
     * @method Grid\Column|Collection currency_name(string $label = null)
     * @method Grid\Column|Collection other_name(string $label = null)
     * @method Grid\Column|Collection price(string $label = null)
     * @method Grid\Column|Collection back_price(string $label = null)
     * @method Grid\Column|Collection is_search(string $label = null)
     * @method Grid\Column|Collection last_time(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection group(string $label = null)
     * @method Grid\Column|Collection content(string $label = null)
     * @method Grid\Column|Collection required(string $label = null)
     * @method Grid\Column|Collection color(string $label = null)
     * @method Grid\Column|Collection loan_month(string $label = null)
     * @method Grid\Column|Collection amount(string $label = null)
     * @method Grid\Column|Collection interest_rate(string $label = null)
     * @method Grid\Column|Collection interest_amount(string $label = null)
     * @method Grid\Column|Collection leverage(string $label = null)
     * @method Grid\Column|Collection term_days(string $label = null)
     * @method Grid\Column|Collection service_fee(string $label = null)
     * @method Grid\Column|Collection loan_time(string $label = null)
     * @method Grid\Column|Collection end_time(string $label = null)
     * @method Grid\Column|Collection next_time(string $label = null)
     * @method Grid\Column|Collection repay_time(string $label = null)
     * @method Grid\Column|Collection repay_amount(string $label = null)
     * @method Grid\Column|Collection overdue_days(string $label = null)
     * @method Grid\Column|Collection overdue_fee(string $label = null)
     * @method Grid\Column|Collection ordernum(string $label = null)
     * @method Grid\Column|Collection hash(string $label = null)
     * @method Grid\Column|Collection remark(string $label = null)
     * @method Grid\Column|Collection pay_status(string $label = null)
     * @method Grid\Column|Collection loan_id(string $label = null)
     * @method Grid\Column|Collection period(string $label = null)
     * @method Grid\Column|Collection due_amount(string $label = null)
     * @method Grid\Column|Collection btime(string $label = null)
     * @method Grid\Column|Collection etime(string $label = null)
     * @method Grid\Column|Collection overdue_rate(string $label = null)
     * @method Grid\Column|Collection coin_img(string $label = null)
     * @method Grid\Column|Collection rate(string $label = null)
     * @method Grid\Column|Collection contract_address_lp(string $label = null)
     * @method Grid\Column|Collection pancake_cate(string $label = null)
     * @method Grid\Column|Collection is_sync(string $label = null)
     * @method Grid\Column|Collection is_success(string $label = null)
     * @method Grid\Column|Collection precision(string $label = null)
     * @method Grid\Column|Collection describe(string $label = null)
     * @method Grid\Column|Collection cover(string $label = null)
     * @method Grid\Column|Collection is_top(string $label = null)
     * @method Grid\Column|Collection read_min(string $label = null)
     * @method Grid\Column|Collection fake_read_nums(string $label = null)
     * @method Grid\Column|Collection read_nums(string $label = null)
     * @method Grid\Column|Collection pushd_at(string $label = null)
     * @method Grid\Column|Collection total(string $label = null)
     * @method Grid\Column|Collection gift_power(string $label = null)
     * @method Grid\Column|Collection withdraw_rate(string $label = null)
     * @method Grid\Column|Collection node_id(string $label = null)
     * @method Grid\Column|Collection ispop(string $label = null)
     * @method Grid\Column|Collection date(string $label = null)
     * @method Grid\Column|Collection datetime(string $label = null)
     * @method Grid\Column|Collection channel_id(string $label = null)
     * @method Grid\Column|Collection default_lang(string $label = null)
     * @method Grid\Column|Collection money_decimal(string $label = null)
     * @method Grid\Column|Collection usdt_withdraw_enable(string $label = null)
     * @method Grid\Column|Collection fac_withdraw_enable(string $label = null)
     * @method Grid\Column|Collection usdt_withdraw_rate(string $label = null)
     * @method Grid\Column|Collection fac_withdraw_rate(string $label = null)
     * @method Grid\Column|Collection usdt_min_withdraw(string $label = null)
     * @method Grid\Column|Collection usdt_max_withdraw(string $label = null)
     * @method Grid\Column|Collection usdt_daily_max_withdraw(string $label = null)
     * @method Grid\Column|Collection fac_min_withdraw(string $label = null)
     * @method Grid\Column|Collection fac_max_withdraw(string $label = null)
     * @method Grid\Column|Collection fac_daily_max_withdraw(string $label = null)
     * @method Grid\Column|Collection lock_address(string $label = null)
     * @method Grid\Column|Collection power_price(string $label = null)
     * @method Grid\Column|Collection zhi_rate(string $label = null)
     * @method Grid\Column|Collection ceng_rate(string $label = null)
     * @method Grid\Column|Collection address(string $label = null)
     * @method Grid\Column|Collection deep(string $label = null)
     * @method Grid\Column|Collection code(string $label = null)
     * @method Grid\Column|Collection zhi_num(string $label = null)
     * @method Grid\Column|Collection team_num(string $label = null)
     * @method Grid\Column|Collection self_yeji(string $label = null)
     * @method Grid\Column|Collection team_yeji(string $label = null)
     * @method Grid\Column|Collection total_yeji(string $label = null)
     * @method Grid\Column|Collection valid_status(string $label = null)
     * @method Grid\Column|Collection lock_amount(string $label = null)
     * @method Grid\Column|Collection amount_type(string $label = null)
     * @method Grid\Column|Collection before(string $label = null)
     * @method Grid\Column|Collection after(string $label = null)
     * @method Grid\Column|Collection add_type(string $label = null)
     * @method Grid\Column|Collection me_power(string $label = null)
     * @method Grid\Column|Collection team_power(string $label = null)
     * @method Grid\Column|Collection team_valid_count(string $label = null)
     * @method Grid\Column|Collection team_level_id(string $label = null)
     * @method Grid\Column|Collection rate1(string $label = null)
     * @method Grid\Column|Collection rate2(string $label = null)
     * @method Grid\Column|Collection withdraw_usdt_status(string $label = null)
     * @method Grid\Column|Collection withdraw_nadi_status(string $label = null)
     * @method Grid\Column|Collection num(string $label = null)
     * @method Grid\Column|Collection total_amount(string $label = null)
     * @method Grid\Column|Collection is_settlement(string $label = null)
     * @method Grid\Column|Collection total_power(string $label = null)
     * @method Grid\Column|Collection valid_power(string $label = null)
     * @method Grid\Column|Collection expired_power(string $label = null)
     * @method Grid\Column|Collection machine_power(string $label = null)
     * @method Grid\Column|Collection node_power(string $label = null)
     * @method Grid\Column|Collection power_type(string $label = null)
     * @method Grid\Column|Collection power(string $label = null)
     * @method Grid\Column|Collection order_no(string $label = null)
     * @method Grid\Column|Collection nums(string $label = null)
     * @method Grid\Column|Collection other_nums(string $label = null)
     * @method Grid\Column|Collection coin(string $label = null)
     * @method Grid\Column|Collection other_coin(string $label = null)
     * @method Grid\Column|Collection coin_address(string $label = null)
     * @method Grid\Column|Collection coin1_address(string $label = null)
     * @method Grid\Column|Collection total1_amount(string $label = null)
     * @method Grid\Column|Collection extend(string $label = null)
     * @method Grid\Column|Collection no(string $label = null)
     * @method Grid\Column|Collection coin_id(string $label = null)
     * @method Grid\Column|Collection fee(string $label = null)
     * @method Grid\Column|Collection fee_amount(string $label = null)
     * @method Grid\Column|Collection ac_amount(string $label = null)
     * @method Grid\Column|Collection finsh_time(string $label = null)
     * @method Grid\Column|Collection is_push(string $label = null)
     * @method Grid\Column|Collection register_num(string $label = null)
     * @method Grid\Column|Collection backend_recharge_usdt(string $label = null)
     * @method Grid\Column|Collection backend_recharge_coin(string $label = null)
     * @method Grid\Column|Collection recharge_usdt_num(string $label = null)
     * @method Grid\Column|Collection recharge_usdt_count(string $label = null)
     * @method Grid\Column|Collection recharge_coin_num(string $label = null)
     * @method Grid\Column|Collection recharge_coin_count(string $label = null)
     * @method Grid\Column|Collection withdraw_num(string $label = null)
     * @method Grid\Column|Collection withdraw_count(string $label = null)
     * @method Grid\Column|Collection withdraw_fee(string $label = null)
     * @method Grid\Column|Collection power_income(string $label = null)
     * @method Grid\Column|Collection equipment_income(string $label = null)
     * @method Grid\Column|Collection node_income(string $label = null)
     * @method Grid\Column|Collection node_withdraw_income(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection id
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection version
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection order
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection path
     * @property Show\Field|Collection method
     * @property Show\Field|Collection ip
     * @property Show\Field|Collection input
     * @property Show\Field|Collection deleted_at
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection value
     * @property Show\Field|Collection username
     * @property Show\Field|Collection password
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection email
     * @property Show\Field|Collection wx_openid
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection google_two_fa_secret
     * @property Show\Field|Collection google_two_fa_enable
     * @property Show\Field|Collection status
     * @property Show\Field|Collection banner_type
     * @property Show\Field|Collection banner
     * @property Show\Field|Collection tab
     * @property Show\Field|Collection key
     * @property Show\Field|Collection help
     * @property Show\Field|Collection element
     * @property Show\Field|Collection rule
     * @property Show\Field|Collection contract_address
     * @property Show\Field|Collection decimals
     * @property Show\Field|Collection img
     * @property Show\Field|Collection st
     * @property Show\Field|Collection chain_id
     * @property Show\Field|Collection currency_id
     * @property Show\Field|Collection other_id
     * @property Show\Field|Collection currency_name
     * @property Show\Field|Collection other_name
     * @property Show\Field|Collection price
     * @property Show\Field|Collection back_price
     * @property Show\Field|Collection is_search
     * @property Show\Field|Collection last_time
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection group
     * @property Show\Field|Collection content
     * @property Show\Field|Collection required
     * @property Show\Field|Collection color
     * @property Show\Field|Collection loan_month
     * @property Show\Field|Collection amount
     * @property Show\Field|Collection interest_rate
     * @property Show\Field|Collection interest_amount
     * @property Show\Field|Collection leverage
     * @property Show\Field|Collection term_days
     * @property Show\Field|Collection service_fee
     * @property Show\Field|Collection loan_time
     * @property Show\Field|Collection end_time
     * @property Show\Field|Collection next_time
     * @property Show\Field|Collection repay_time
     * @property Show\Field|Collection repay_amount
     * @property Show\Field|Collection overdue_days
     * @property Show\Field|Collection overdue_fee
     * @property Show\Field|Collection ordernum
     * @property Show\Field|Collection hash
     * @property Show\Field|Collection remark
     * @property Show\Field|Collection pay_status
     * @property Show\Field|Collection loan_id
     * @property Show\Field|Collection period
     * @property Show\Field|Collection due_amount
     * @property Show\Field|Collection btime
     * @property Show\Field|Collection etime
     * @property Show\Field|Collection overdue_rate
     * @property Show\Field|Collection coin_img
     * @property Show\Field|Collection rate
     * @property Show\Field|Collection contract_address_lp
     * @property Show\Field|Collection pancake_cate
     * @property Show\Field|Collection is_sync
     * @property Show\Field|Collection is_success
     * @property Show\Field|Collection precision
     * @property Show\Field|Collection describe
     * @property Show\Field|Collection cover
     * @property Show\Field|Collection is_top
     * @property Show\Field|Collection read_min
     * @property Show\Field|Collection fake_read_nums
     * @property Show\Field|Collection read_nums
     * @property Show\Field|Collection pushd_at
     * @property Show\Field|Collection total
     * @property Show\Field|Collection gift_power
     * @property Show\Field|Collection withdraw_rate
     * @property Show\Field|Collection node_id
     * @property Show\Field|Collection ispop
     * @property Show\Field|Collection date
     * @property Show\Field|Collection datetime
     * @property Show\Field|Collection channel_id
     * @property Show\Field|Collection default_lang
     * @property Show\Field|Collection money_decimal
     * @property Show\Field|Collection usdt_withdraw_enable
     * @property Show\Field|Collection fac_withdraw_enable
     * @property Show\Field|Collection usdt_withdraw_rate
     * @property Show\Field|Collection fac_withdraw_rate
     * @property Show\Field|Collection usdt_min_withdraw
     * @property Show\Field|Collection usdt_max_withdraw
     * @property Show\Field|Collection usdt_daily_max_withdraw
     * @property Show\Field|Collection fac_min_withdraw
     * @property Show\Field|Collection fac_max_withdraw
     * @property Show\Field|Collection fac_daily_max_withdraw
     * @property Show\Field|Collection lock_address
     * @property Show\Field|Collection power_price
     * @property Show\Field|Collection zhi_rate
     * @property Show\Field|Collection ceng_rate
     * @property Show\Field|Collection address
     * @property Show\Field|Collection deep
     * @property Show\Field|Collection code
     * @property Show\Field|Collection zhi_num
     * @property Show\Field|Collection team_num
     * @property Show\Field|Collection self_yeji
     * @property Show\Field|Collection team_yeji
     * @property Show\Field|Collection total_yeji
     * @property Show\Field|Collection valid_status
     * @property Show\Field|Collection lock_amount
     * @property Show\Field|Collection amount_type
     * @property Show\Field|Collection before
     * @property Show\Field|Collection after
     * @property Show\Field|Collection add_type
     * @property Show\Field|Collection me_power
     * @property Show\Field|Collection team_power
     * @property Show\Field|Collection team_valid_count
     * @property Show\Field|Collection team_level_id
     * @property Show\Field|Collection rate1
     * @property Show\Field|Collection rate2
     * @property Show\Field|Collection withdraw_usdt_status
     * @property Show\Field|Collection withdraw_nadi_status
     * @property Show\Field|Collection num
     * @property Show\Field|Collection total_amount
     * @property Show\Field|Collection is_settlement
     * @property Show\Field|Collection total_power
     * @property Show\Field|Collection valid_power
     * @property Show\Field|Collection expired_power
     * @property Show\Field|Collection machine_power
     * @property Show\Field|Collection node_power
     * @property Show\Field|Collection power_type
     * @property Show\Field|Collection power
     * @property Show\Field|Collection order_no
     * @property Show\Field|Collection nums
     * @property Show\Field|Collection other_nums
     * @property Show\Field|Collection coin
     * @property Show\Field|Collection other_coin
     * @property Show\Field|Collection coin_address
     * @property Show\Field|Collection coin1_address
     * @property Show\Field|Collection total1_amount
     * @property Show\Field|Collection extend
     * @property Show\Field|Collection no
     * @property Show\Field|Collection coin_id
     * @property Show\Field|Collection fee
     * @property Show\Field|Collection fee_amount
     * @property Show\Field|Collection ac_amount
     * @property Show\Field|Collection finsh_time
     * @property Show\Field|Collection is_push
     * @property Show\Field|Collection register_num
     * @property Show\Field|Collection backend_recharge_usdt
     * @property Show\Field|Collection backend_recharge_coin
     * @property Show\Field|Collection recharge_usdt_num
     * @property Show\Field|Collection recharge_usdt_count
     * @property Show\Field|Collection recharge_coin_num
     * @property Show\Field|Collection recharge_coin_count
     * @property Show\Field|Collection withdraw_num
     * @property Show\Field|Collection withdraw_count
     * @property Show\Field|Collection withdraw_fee
     * @property Show\Field|Collection power_income
     * @property Show\Field|Collection equipment_income
     * @property Show\Field|Collection node_income
     * @property Show\Field|Collection node_withdraw_income
     *
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection path(string $label = null)
     * @method Show\Field|Collection method(string $label = null)
     * @method Show\Field|Collection ip(string $label = null)
     * @method Show\Field|Collection input(string $label = null)
     * @method Show\Field|Collection deleted_at(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection wx_openid(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection google_two_fa_secret(string $label = null)
     * @method Show\Field|Collection google_two_fa_enable(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection banner_type(string $label = null)
     * @method Show\Field|Collection banner(string $label = null)
     * @method Show\Field|Collection tab(string $label = null)
     * @method Show\Field|Collection key(string $label = null)
     * @method Show\Field|Collection help(string $label = null)
     * @method Show\Field|Collection element(string $label = null)
     * @method Show\Field|Collection rule(string $label = null)
     * @method Show\Field|Collection contract_address(string $label = null)
     * @method Show\Field|Collection decimals(string $label = null)
     * @method Show\Field|Collection img(string $label = null)
     * @method Show\Field|Collection st(string $label = null)
     * @method Show\Field|Collection chain_id(string $label = null)
     * @method Show\Field|Collection currency_id(string $label = null)
     * @method Show\Field|Collection other_id(string $label = null)
     * @method Show\Field|Collection currency_name(string $label = null)
     * @method Show\Field|Collection other_name(string $label = null)
     * @method Show\Field|Collection price(string $label = null)
     * @method Show\Field|Collection back_price(string $label = null)
     * @method Show\Field|Collection is_search(string $label = null)
     * @method Show\Field|Collection last_time(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection group(string $label = null)
     * @method Show\Field|Collection content(string $label = null)
     * @method Show\Field|Collection required(string $label = null)
     * @method Show\Field|Collection color(string $label = null)
     * @method Show\Field|Collection loan_month(string $label = null)
     * @method Show\Field|Collection amount(string $label = null)
     * @method Show\Field|Collection interest_rate(string $label = null)
     * @method Show\Field|Collection interest_amount(string $label = null)
     * @method Show\Field|Collection leverage(string $label = null)
     * @method Show\Field|Collection term_days(string $label = null)
     * @method Show\Field|Collection service_fee(string $label = null)
     * @method Show\Field|Collection loan_time(string $label = null)
     * @method Show\Field|Collection end_time(string $label = null)
     * @method Show\Field|Collection next_time(string $label = null)
     * @method Show\Field|Collection repay_time(string $label = null)
     * @method Show\Field|Collection repay_amount(string $label = null)
     * @method Show\Field|Collection overdue_days(string $label = null)
     * @method Show\Field|Collection overdue_fee(string $label = null)
     * @method Show\Field|Collection ordernum(string $label = null)
     * @method Show\Field|Collection hash(string $label = null)
     * @method Show\Field|Collection remark(string $label = null)
     * @method Show\Field|Collection pay_status(string $label = null)
     * @method Show\Field|Collection loan_id(string $label = null)
     * @method Show\Field|Collection period(string $label = null)
     * @method Show\Field|Collection due_amount(string $label = null)
     * @method Show\Field|Collection btime(string $label = null)
     * @method Show\Field|Collection etime(string $label = null)
     * @method Show\Field|Collection overdue_rate(string $label = null)
     * @method Show\Field|Collection coin_img(string $label = null)
     * @method Show\Field|Collection rate(string $label = null)
     * @method Show\Field|Collection contract_address_lp(string $label = null)
     * @method Show\Field|Collection pancake_cate(string $label = null)
     * @method Show\Field|Collection is_sync(string $label = null)
     * @method Show\Field|Collection is_success(string $label = null)
     * @method Show\Field|Collection precision(string $label = null)
     * @method Show\Field|Collection describe(string $label = null)
     * @method Show\Field|Collection cover(string $label = null)
     * @method Show\Field|Collection is_top(string $label = null)
     * @method Show\Field|Collection read_min(string $label = null)
     * @method Show\Field|Collection fake_read_nums(string $label = null)
     * @method Show\Field|Collection read_nums(string $label = null)
     * @method Show\Field|Collection pushd_at(string $label = null)
     * @method Show\Field|Collection total(string $label = null)
     * @method Show\Field|Collection gift_power(string $label = null)
     * @method Show\Field|Collection withdraw_rate(string $label = null)
     * @method Show\Field|Collection node_id(string $label = null)
     * @method Show\Field|Collection ispop(string $label = null)
     * @method Show\Field|Collection date(string $label = null)
     * @method Show\Field|Collection datetime(string $label = null)
     * @method Show\Field|Collection channel_id(string $label = null)
     * @method Show\Field|Collection default_lang(string $label = null)
     * @method Show\Field|Collection money_decimal(string $label = null)
     * @method Show\Field|Collection usdt_withdraw_enable(string $label = null)
     * @method Show\Field|Collection fac_withdraw_enable(string $label = null)
     * @method Show\Field|Collection usdt_withdraw_rate(string $label = null)
     * @method Show\Field|Collection fac_withdraw_rate(string $label = null)
     * @method Show\Field|Collection usdt_min_withdraw(string $label = null)
     * @method Show\Field|Collection usdt_max_withdraw(string $label = null)
     * @method Show\Field|Collection usdt_daily_max_withdraw(string $label = null)
     * @method Show\Field|Collection fac_min_withdraw(string $label = null)
     * @method Show\Field|Collection fac_max_withdraw(string $label = null)
     * @method Show\Field|Collection fac_daily_max_withdraw(string $label = null)
     * @method Show\Field|Collection lock_address(string $label = null)
     * @method Show\Field|Collection power_price(string $label = null)
     * @method Show\Field|Collection zhi_rate(string $label = null)
     * @method Show\Field|Collection ceng_rate(string $label = null)
     * @method Show\Field|Collection address(string $label = null)
     * @method Show\Field|Collection deep(string $label = null)
     * @method Show\Field|Collection code(string $label = null)
     * @method Show\Field|Collection zhi_num(string $label = null)
     * @method Show\Field|Collection team_num(string $label = null)
     * @method Show\Field|Collection self_yeji(string $label = null)
     * @method Show\Field|Collection team_yeji(string $label = null)
     * @method Show\Field|Collection total_yeji(string $label = null)
     * @method Show\Field|Collection valid_status(string $label = null)
     * @method Show\Field|Collection lock_amount(string $label = null)
     * @method Show\Field|Collection amount_type(string $label = null)
     * @method Show\Field|Collection before(string $label = null)
     * @method Show\Field|Collection after(string $label = null)
     * @method Show\Field|Collection add_type(string $label = null)
     * @method Show\Field|Collection me_power(string $label = null)
     * @method Show\Field|Collection team_power(string $label = null)
     * @method Show\Field|Collection team_valid_count(string $label = null)
     * @method Show\Field|Collection team_level_id(string $label = null)
     * @method Show\Field|Collection rate1(string $label = null)
     * @method Show\Field|Collection rate2(string $label = null)
     * @method Show\Field|Collection withdraw_usdt_status(string $label = null)
     * @method Show\Field|Collection withdraw_nadi_status(string $label = null)
     * @method Show\Field|Collection num(string $label = null)
     * @method Show\Field|Collection total_amount(string $label = null)
     * @method Show\Field|Collection is_settlement(string $label = null)
     * @method Show\Field|Collection total_power(string $label = null)
     * @method Show\Field|Collection valid_power(string $label = null)
     * @method Show\Field|Collection expired_power(string $label = null)
     * @method Show\Field|Collection machine_power(string $label = null)
     * @method Show\Field|Collection node_power(string $label = null)
     * @method Show\Field|Collection power_type(string $label = null)
     * @method Show\Field|Collection power(string $label = null)
     * @method Show\Field|Collection order_no(string $label = null)
     * @method Show\Field|Collection nums(string $label = null)
     * @method Show\Field|Collection other_nums(string $label = null)
     * @method Show\Field|Collection coin(string $label = null)
     * @method Show\Field|Collection other_coin(string $label = null)
     * @method Show\Field|Collection coin_address(string $label = null)
     * @method Show\Field|Collection coin1_address(string $label = null)
     * @method Show\Field|Collection total1_amount(string $label = null)
     * @method Show\Field|Collection extend(string $label = null)
     * @method Show\Field|Collection no(string $label = null)
     * @method Show\Field|Collection coin_id(string $label = null)
     * @method Show\Field|Collection fee(string $label = null)
     * @method Show\Field|Collection fee_amount(string $label = null)
     * @method Show\Field|Collection ac_amount(string $label = null)
     * @method Show\Field|Collection finsh_time(string $label = null)
     * @method Show\Field|Collection is_push(string $label = null)
     * @method Show\Field|Collection register_num(string $label = null)
     * @method Show\Field|Collection backend_recharge_usdt(string $label = null)
     * @method Show\Field|Collection backend_recharge_coin(string $label = null)
     * @method Show\Field|Collection recharge_usdt_num(string $label = null)
     * @method Show\Field|Collection recharge_usdt_count(string $label = null)
     * @method Show\Field|Collection recharge_coin_num(string $label = null)
     * @method Show\Field|Collection recharge_coin_count(string $label = null)
     * @method Show\Field|Collection withdraw_num(string $label = null)
     * @method Show\Field|Collection withdraw_count(string $label = null)
     * @method Show\Field|Collection withdraw_fee(string $label = null)
     * @method Show\Field|Collection power_income(string $label = null)
     * @method Show\Field|Collection equipment_income(string $label = null)
     * @method Show\Field|Collection node_income(string $label = null)
     * @method Show\Field|Collection node_withdraw_income(string $label = null)
     */
    class Show {}

    /**
     * @method \Dcat\Admin\Form\Extend\Distpicker\Form\Distpicker distpicker(...$params)
     * @method \Dcat\Admin\Form\Extend\Diyforms\Form\DiyForm diyForm(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Iconimg iconimg(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Photo photo(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Photos photos(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Video video(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Audio audio(...$params)
     * @method \Dcat\Admin\Form\Extend\FormMedia\Form\Field\Files files(...$params)
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     * @method $this distpicker(...$params)
     */
    class Column {}

    /**
     * @method \Dcat\Admin\Form\Extend\Distpicker\Filter\DistpickerFilter distpicker(...$params)
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     * @method $this diyForm(...$params)
     */
    class Field {}
}
