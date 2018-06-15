CREATE TABLE `hjmall_mail_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `send_mail` longtext COMMENT '发件人邮箱',
  `send_pwd` varchar(255) DEFAULT NULL COMMENT '授权码',
  `send_name` varchar(255) DEFAULT NULL COMMENT '发件人名称',
  `receive_mail` longtext COMMENT '收件人邮箱 多个用英文逗号隔开',
  `status` int(11) DEFAULT NULL COMMENT '是否开启邮件通知 0--关闭 1--开启',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

/* 拼团商品表 */
ALTER TABLE `hjmall_pt_goods`
  MODIFY COLUMN `virtual_sales` INT(11) UNSIGNED NULL DEFAULT 0
  COMMENT '虚拟销量';

/* 2017-12-15 */
ALTER TABLE `hjmall_pt_goods`
  ADD COLUMN `buy_limit` INT(11) UNSIGNED NULL DEFAULT 0
COMMENT '限购数量';

ALTER TABLE `hjmall_setting`
  ADD COLUMN `price_type` INT(11) NULL DEFAULT 0
COMMENT '分销金额 0--百分比金额  1--固定金额';

ALTER TABLE `hjmall_goods`
  ADD COLUMN `share_type` INT(11) NULL DEFAULT 0
COMMENT '佣金配比 0--百分比 1--固定金额';