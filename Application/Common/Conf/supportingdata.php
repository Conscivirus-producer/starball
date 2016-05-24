<?php
return array(
	//'配置项'=>'配置值'
	'ITEMSIZE' => array(
		'1' => array('新生儿', '40', '50'),
		'2' => array('1个月', '54', '54'),
		'3' => array('3个月', '60', '60'),
		'4' => array('6个月', '67', '67'),
		'5' => array('9个月', '71', '71'),
		'6' => array('12个月', '74', '74'),
        '7' => array('18个月', '81', '81'),
        '8' => array('2岁', '83', '88'),
        '9' => array('3岁', '91', '96'),
        '10' => array('4岁', '99', '104'),
        '11' => array('5岁', '105', '110'),
        '12' => array('6岁', '111', '116'),
        '13' => array('7岁', '117', '122'),
		'14' => array('8岁', '123', '128'),
		'15' => array('9岁', '129', '134'),
		'16' => array('10岁', '134', '140'),
		'17' => array('11岁', '136', '146'),
		'18' => array('12岁', '141', '152'),
		'19' => array('13岁', '147', '159'),
		'20' => array('14岁', '153', '164'),
		'21' => array('15岁', '159', '171'),
		'22' => array('16岁', '165', '176'),
		'23' => array('18岁', '', ''),
		'24' => array('均码', '', ''),
    ),
    
    'USERTYPE' => array(
    	//usertype => array(grade, gender), grade:1-baby, 2-boy/girl
    	'baby' => array('1', ''),
    	'boy' => array('2', 'M'),
    	'girl' => array('2', 'F'),
	),
	'CURRENCY' => array(
		'HKD' => '$HK',
		'CNY' => 'Ұ',
	),
	//订单状态代码与描述对应关系
	'ORDERSTATUS' => array(
		'N' => '等待支付',
		'P' => '已支付',
		'C1' => '订单取消中',
		'C2' => '订单取消中',
		'C3' => '取消成功',
		'D' => '已发货',
		'V' => '已收货',
	),
	//跳到登录页面，如果要回到之前页面，在这里设置
	'FROM_ACTION' => array(
		'account' => array('url'=>'User/index', 'params'=>array('tab' => 'account')),
		'order' => array('url'=>'User/index', 'params'=>array('tab' => 'order')),
		'coupon' => array('url'=>'User/index', 'params'=>array('tab' => 'coupon')),
		'giftcard' => array('url'=>'User/index', 'params'=>array('tab' => 'giftcard')),
		'shoppinglist' => array('url'=>'Cart/index', 'params'=>array()),
		'home' => array('url'=>'Home/index', 'params'=>array()),
	),
	//默认语言汇率设置
	'LANG_CURRENCY' => array(
		'zh-cn' => 'CNY',
		'zh-tw' => 'HKD'
	),
	'SESSION_KEYS' => array(
		'userName' => 'starballkids_userName',
		'userId' => 'starballkids_userId',
	),
	'COUNTRY_LIST' => array(
		//代码,语言key
		'cn'=>'china',
		'hk'=>'hk'
	),
	'CHINA_PROVINCE_LIST' => array(
		//'省份代号' => 名称,首重费用,每续重0.5KG费用
		'11'=>array('北京市','22','6.5'),
		'12'=>array('天津市','22','7'),
		'13'=>array('河北省','22','7'),
		'14'=>array('山西省','22','7'),
		'15'=>array('内蒙古自治区','22','9'),
		'21'=>array('辽宁省','22','9'),
		'22'=>array('吉林省','22','9'),
		'23'=>array('黑龙江省','22','9'),
		'31'=>array('上海市','22','6.5'),
		'32'=>array('江苏省','22','6.5'),
		'33'=>array('浙江省','22','6.5'),
		'34'=>array('安徽省','22','7'),
		'35'=>array('福建省','22','6.5'),
		'36'=>array('江西省','22','7'),
		'37'=>array('山东省','22','7'),
		'41'=>array('河南省','22','7'),
		'42'=>array('湖北省','22','7'),
		'43'=>array('湖南省','22','7'),
		'44'=>array('广东省','12','1'),
		'45'=>array('广西壮族自治区','22','7'),
		'46'=>array('海南省','22','7'),
		'50'=>array('重庆市','22','7'),
		'51'=>array('四川省','22','7'),
		'52'=>array('贵州省','22','7'),
		'53'=>array('云南省','22','7'),
		'54'=>array('西藏自治区','22','9'),
		'61'=>array('陕西省','22','7'),
		'62'=>array('甘肃省','22','9'),
		'63'=>array('青海省','22','9'),
		'64'=>array('宁夏回族自治区','22','9'),
		'65'=>array('新疆维吾尔自治区','22','9')
	),
	'SHIPPING_FEE_SETTING' => array(
		'EXTENDED_WEIGHT_BENCHMARK' => '0.5',
		'FREE_BENCHMARK_CNY' => '840',
		'FREE_BENCHMARK_HKD' => '1000',
		'HK_DEFAULT_COST' => '35'
	),
	'EXCHANGE_RATE_HKD_TO_CNY' => '0.84'
);