<?php
//定义回调URL通用的URL
define('URL_CALLBACK', 'http://starballkids.com/Starball/Home/callback/type/');

return array(
	//'配置项'=>'配置值'
	//新浪微博配置
	'THINK_SDK_SINA' => array(
		'APP_KEY'    => '3323085710', //应用注册成功后分配的 APP ID
		'APP_SECRET' => 'eebe5b714553629870aad81c1e7ec9f7', //应用注册成功后分配的KEY
		'CALLBACK'   => URL_CALLBACK . 'sina',
	),
	
    //微信登录
    'THINK_SDK_WEIXIN' => array(
        'APP_KEY'    => 'wxfda566293af59f58', //应用注册成功后分配的 APP ID
        'APP_SECRET' => 'e80ce93d11bfbf9c6acc9b53e4f40b91', //应用注册成功后分配的KEY
        'CALLBACK'   => URL_CALLBACK . 'weixin',
    ),
    
    //Facebook登录
    'THINK_SDK_FACEBOOK' => array(
        'APP_KEY'    => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK'   => URL_CALLBACK . 'facebook',
    ),
);