ALTER TABLE  `t_supportingdata` ADD  `type` VARCHAR( 10 ) NOT NULL COMMENT  '1-文字;2-图片url' AFTER  `value`;
update t_supportingdata set type = '1';
ALTER TABLE  `t_brand` ADD  `sizeDescription` VARCHAR( 200 ) NOT NULL COMMENT  '品牌的尺码信息,是一张图片';