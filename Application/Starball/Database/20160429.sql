ALTER TABLE  `t_order` ADD  `shippingMethod` VARCHAR( 200 ) NOT NULL COMMENT  '物流方式' AFTER  `shippingAddress`;
ALTER TABLE  `t_order` ADD  `orderDate` DATETIME NOT NULL COMMENT  '订单时间,最后提交订单的时间' AFTER  `updatedDate`;
ALTER TABLE  `t_order` ADD  `shippingOrderNumber` VARCHAR( 100 ) NOT NULL COMMENT  '物流公司的快递单号' AFTER  `shippingAddress`;
ALTER TABLE  `t_ordercancel` CHANGE  `status`  `status` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'N-New,发起退货; A-Accept,卖家同意退货; V-Verified,卖家已收到退货; D-Done,退款成功 C-Cancel,取消退货';