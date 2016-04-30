ALTER TABLE  `t_orderbill` ADD  `refundNumber` VARCHAR( 50 ) NOT NULL COMMENT  '如果有退款,为退款单号' AFTER  `billNumber`;
ALTER TABLE  `t_orderbill` ADD  `orderItemId` INT( 32 ) NOT NULL COMMENT  '退款时才有值,参考t_orderitem->id' AFTER  `refundNumber` ,
ADD INDEX (  `orderItemId` );