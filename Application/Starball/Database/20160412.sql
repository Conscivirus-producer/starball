ALTER TABLE  `t_orderitem` CHANGE  `orderNumber`  `orderId` INT( 32 ) NOT NULL;
ALTER TABLE  `t_orderbill` ADD  `billNumber` VARCHAR( 50 ) NOT NULL AFTER  `orderNumber` ,
ADD INDEX (  `billNumber` );
ALTER TABLE  `t_orderbill` ADD INDEX (  `orderNumber` );