ALTER TABLE  `t_orderitem` ADD  `brandName` VARCHAR( 100 ) NOT NULL AFTER  `itemName` ,
ADD  `itemImage` VARCHAR( 200 ) NOT NULL AFTER  `brandName` ,
ADD  `itemColor` VARCHAR( 100 ) NOT NULL AFTER  `itemImage`;