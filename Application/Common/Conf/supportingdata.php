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
		'N' => '新订单',
		'P' => '已支付',
		'C' => '已取消',
		'D' => '已完成',
	),
	//跳到登录页面，如果要回到之前页面，在这里设置
	'FROM_ACTION' => array(
		'account' => array('url'=>'User/index', 'params'=>array('tab' => 'account')),
		'order' => array('url'=>'User/index', 'params'=>array('tab' => 'order')),
		'coupon' => array('url'=>'User/index', 'params'=>array('tab' => 'coupon')),
		'giftcard' => array('url'=>'User/index', 'params'=>array('tab' => 'giftcard')),
		'shoppinglist' => array('url'=>'Cart/delivery', 'params'=>array()),
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
	)
);