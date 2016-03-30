<?php
return array(
	//数据库配置信息
	'IS_DEV'=>'true',// 本地开发时设成true,服务器上这项值 为'false'
	'BUCKET'=>'videodev',//改成本地设置,开发为'videodev',产品环境为'video'
	'QINIU_VIDEO_PREFIX'		  => 'http://7xpw8p.com2.z0.glb.qiniucdn.com/', //七牛视频存储路径
	
	//数据库配置信息
	'DB_TYPE'=>'mysql',// 数据库类型
	'DB_HOST'=>'localhost',// 服务器地址
	//'DB_HOST'=>'www.sven.com',// 服务器地址
	'DB_NAME'=>'starball_schema',// 数据库名
	'DB_USER'=>'root',// 用户名
	'DB_PWD'=>'',// 密码
	'DB_PORT'=>3306,// 端口
	'DB_PREFIX'=>'',// 数据库表前缀
	'DB_CHARSET'=>'utf8',// 数据库字符集
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL)
);