ALTER TABLE  `t_order` DROP INDEX  `orderNumber` ,
ADD INDEX  `orderNumber` (  `orderNumber` );