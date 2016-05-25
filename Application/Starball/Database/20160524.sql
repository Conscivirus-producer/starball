ALTER TABLE  `t_shippingaddress` ADD  `deliveryType` VARCHAR( 3 ) NOT NULL COMMENT  '0-地铁站自取;1-送货上门.仅针对香港地区';
INSERT INTO `t_supportingdata` (`key`, `value`, `remark`) VALUES 
('SHIPPING_COMMON_CLOTH_WEIGHT', '0.2', '衣服的一般重量,单位为千克'), 
('SHIPPING_COMMON_SHOE_WEIGHT', '0.25', '鞋子的一般重量,单位为千克');