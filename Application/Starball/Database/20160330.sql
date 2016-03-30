ALTER TABLE `t_item` DROP `link`;
ALTER TABLE `t_item` DROP `appendWords`;
ALTER TABLE `t_order` ADD  `addtionalGreetings` VARCHAR( 500 ) NOT NULL;
ALTER TABLE  `t_inventory` ADD  `inventoryId` INT( 32 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
update `t_inventory` set inventoryId = -inventoryId;