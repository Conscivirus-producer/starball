ALTER TABLE  `t_shippingaddress` ADD  `deliveryType` VARCHAR( 3 ) NOT NULL COMMENT  '0-地铁站自取;1-送货上门.仅针对香港地区';
INSERT INTO `t_supportingdata` (`key`, `value`, `remark`) VALUES 
('SHIPPING_COMMON_CLOTH_WEIGHT', '0.2', '衣服的一般重量,单位为千克'), 
('SHIPPING_COMMON_SHOE_WEIGHT', '0.25', '鞋子的一般重量,单位为千克'),
('SHIPPING_HK_DEFAULT_COST', '35', '香港地区上货上门统一价,单位为HKD'),
('SHIPPING_CN_WEIGHT_BENCHMARK', '0.5', '国内运费,续重最小计量数,单位为千克'),
('SHIPPING_FREE_BENCHMARK_HKD', '1000', '免运费港币金额'),
('SHIPPING_FREE_BENCHMARK_CNY', '840', '免运费人民币金额');