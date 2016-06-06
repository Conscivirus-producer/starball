ALTER TABLE  `t_favoriteitem` ADD  `createdDate` DATETIME NOT NULL AFTER  `userId`;
ALTER TABLE  `t_favoriteitem` ADD  `itemName` VARCHAR( 100 ) NOT NULL AFTER  `userId` ,
ADD  `brandName` VARCHAR( 100 ) NOT NULL AFTER  `itemName` ,
ADD  `itemImage` VARCHAR( 200 ) NOT NULL AFTER  `brandName` ,
ADD  `itemColor` VARCHAR( 100 ) NOT NULL AFTER  `itemImage` ,
ADD  `price` VARCHAR( 100 ) NOT NULL AFTER  `itemColor`;
ALTER TABLE  `t_favoriteitem` ADD  `status` VARCHAR( 3 ) NOT NULL DEFAULT  '1' COMMENT  '1-正常;0-删除' AFTER  `price`;