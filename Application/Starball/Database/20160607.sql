ALTER TABLE  `t_coupon` ADD  `discountAmount` VARCHAR( 10 ) NOT NULL COMMENT  '抵销的金额' AFTER  `discountRate`;
ALTER TABLE  `t_coupon` CHANGE  `discountRate`  `discountRate` INT( 3 ) NOT NULL COMMENT  '折扣率';
ALTER TABLE  `t_coupon` COMMENT =  '优惠券,discountRate和discountAmount只能有一个有值';