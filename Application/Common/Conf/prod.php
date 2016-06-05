<?php
return array(
	'IS_DEV'=>'false',// 本地开发时设成true,服务器上这项值 为'false'
	'IS_TEST'=>'true',//测试的时候使用,比如测试下运费全是零,方便测试支付
	
	//数据库配置信息
	'DB_TYPE'=>'mysql',// 数据库类型
	'DB_HOST'=>'localhost',// 服务器地址
	'DB_NAME'=>'test_schema',// 数据库名
	'DB_USER'=>'root',// 用户名
	'DB_PWD'=>'wang123456',// 密码
	'DB_PORT'=>3306,// 端口
	'DB_PREFIX'=>'',// 数据库表前缀
	'DB_CHARSET'=>'utf8',// 数据库字符集
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
	'URL_MODEL' => '2',
	'DEFAULT_MODULE'        =>  'Starball',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Home', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    
    "SELLER_EMAIL_ADDRESS"  =>   'zhaominqiu@gmail.com',
);