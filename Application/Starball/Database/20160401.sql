ALTER TABLE `t_orderitem` DROP `unshippedQuantity`;
ALTER TABLE  `t_order` ADD  `currency` VARCHAR( 10 ) NOT NULL AFTER  `totalAmount`l;
ALTER TABLE  `t_orderitem` CHANGE  `itemSize`  `itemSize` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '参照数据库表t_inventory';
ALTER TABLE  `t_orderitem` ADD  `updatedDate` DATETIME NOT NULL;
ALTER TABLE  `t_orderitem` ADD  `brandName` VARCHAR( 100 ) NOT NULL AFTER  `itemName` ,
ADD  `itemImage` VARCHAR( 200 ) NOT NULL AFTER  `brandName` ,
ADD  `itemColor` VARCHAR( 100 ) NOT NULL AFTER  `image`;