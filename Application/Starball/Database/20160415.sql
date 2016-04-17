ALTER TABLE  `t_order` CHANGE  `totalAmount`  `totalAmount` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '商品总价格';
ALTER TABLE  `t_order` ADD  `shippingFee` VARCHAR( 50 ) NOT NULL COMMENT  '运费,由买家承担' AFTER  `totalAmount`;
ALTER TABLE  `t_order` ADD  `totalFee` VARCHAR( 50 ) NOT NULL COMMENT  '订单总费用,商品总价格(totalAmount) + 运费(totalFee)' AFTER  `shippingFee`;
ALTER TABLE  `t_order` CHANGE  `shippingAddress`  `shippingAddress` INT( 32 ) NOT NULL COMMENT  '参考t_shippingaddress->addressId';
ALTER TABLE `t_order` DROP `contactName`;
ALTER TABLE `t_order` DROP `contactPhone`;
ALTER TABLE `t_order` DROP `userAvailabeTime`;

ALTER TABLE `t_shippingaddress` DROP `name`;

ALTER TABLE  `t_shippingaddress` ADD  `postCode` VARCHAR( 20 ) NOT NULL AFTER  `address` ,
ADD  `country` VARCHAR( 20 ) NOT NULL COMMENT  '国家代码,有固定的支持国家列表' AFTER  `postCode`;
ALTER TABLE  `t_shippingaddress` ADD  `city` VARCHAR( 50 ) NOT NULL AFTER  `address`;
ALTER TABLE  `t_shippingaddress` ADD  `moreDetails` VARCHAR( 500 ) NOT NULL COMMENT  '更多信息希望让卖家知道的' AFTER  `country`;
ALTER TABLE  `t_shippingaddress` ADD  `addressName` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '给地址命名以方便查询' AFTER  `addressId`;
ALTER TABLE  `t_shippingaddress` ADD  `contactName` VARCHAR( 50 ) NOT NULL COMMENT  '联系人姓名' AFTER  `addressName` ,
ADD  `contactGender` INT NOT NULL COMMENT  '联系人性别' AFTER  `contactName`;
ALTER TABLE  `t_shippingaddress` CHANGE  `contactGender`  `contactGender` VARCHAR( 20 ) NOT NULL COMMENT  '联系人性别';