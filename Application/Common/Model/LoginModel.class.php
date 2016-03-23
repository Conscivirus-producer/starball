<?php
	namespace Common\Model;
	use Think\Model;
	class LoginModel extends Model {
		protected $trueTableName = 't_user';
		
		protected $_validate = array(
	        array('userName', 'require', '用户名不能为空！'), //默认情况下用正则进行验证
	        array('password', 'require', '登录密码不能为空！'), // 默认情况下用正则进行验证
		);
		
		protected $_auto = array(
	        array('password', 'md5', 3, 'function') , // 对password字段使用md5函数处理
	        array('lastDate', 'time', 1, 'function'), // 对lastdate字段在登录的时候写入当前时间戳
	        array('lastIp', 'get_client_ip', 1, 'function'), // 对lastip字段在登录的时候写入当前登录ip地址
		);	
	}