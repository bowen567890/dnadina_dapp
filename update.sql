
alter table `users_machine` add column `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '矿机状态 1-进行中 2-已到期 3-永久生效' after `is_settlement`;
alter table `users_machine` add column  `is_active` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '激活状态 0-待激活 1-已激活' after `status`;
alter table `users_machine` add column `active_time` datetime DEFAULT NULL COMMENT '激活时间'  after `is_active`;
alter table `users_machine` add column `active_price` decimal(30,18) DEFAULT NULL COMMENT '激活价格' after `active_time`;
alter table `users_machine` add column `active_pay` decimal(20,6) DEFAULT NULL COMMENT '激活支付价格'  after `active_price`;

UPDATE `currency_exchange` SET `price` = 3.333333333330000000, `back_price` = 0.300000000000000000 WHERE `id` = 1;

UPDATE `currency` SET `contract_address` = '0x964dF60C9a6865bB5d722A72809AfdB38AEdE51E' WHERE `id` = 2;

=========

alter table `settings` add column  `activation_power_price` decimal(15,4) DEFAULT  NULL COMMENT '激活矿机1G算力≈U' after power_price

UPDATE `settings` SET `activation_power_price` = 50

-- 新增余额销毁提现数量到黑洞  / 添加一笔数据到website_analyze/链上销毁同理添加一笔数量 SettlementCommand.php 添加数量到流通

alter table `website_analyze` add column `circulation_volume` decimal(30,6) DEFAULT '0' COMMENT '流通总量' after `node_withdraw_income`;
alter table `website_analyze` add column `destroy_volume` decimal(30,6) DEFAULT '0' COMMENT '销毁总量' after `circulation_volume`;
