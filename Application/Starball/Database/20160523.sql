ALTER TABLE  `t_item` ADD  `weight` VARCHAR( 10 ) NOT NULL COMMENT  '商品重量,默认为系统设置' AFTER  `categoryId`;
ALTER TABLE  `t_item` ADD  `extraShippingFee` VARCHAR( 10 ) NOT NULL DEFAULT  '0' COMMENT  '对于某些特殊的商品所需要的额外物流费用,默认为0' AFTER  `discount`;
ALTER TABLE  `t_shippingaddress` ADD  `province` VARCHAR( 5 ) NOT NULL COMMENT  '省份代号' AFTER  `address`;
update `t_item` set weight = 0.2 WHERE categoryId in (select categoryId from t_category where type = 1);