ALTER TABLE  `t_itemprice` ADD  `priceId` INT( 32 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
UPDATE `t_itemprice` SET `priceId`= -priceId;
ALTER TABLE  `t_itemprice` ADD  `updatedDate` DATETIME NOT NULL;