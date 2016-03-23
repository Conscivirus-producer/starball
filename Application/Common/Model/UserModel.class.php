<?php
	namespace Common\Model;
	use Think\Model;
	class UserModel extends Model {
		protected $trueTableName = 't_user';
		
		protected $_validate = array(
	        array('userName', 'require', '用户名不能为空！'), //默认情况下用正则进行验证
	        array('userName', '/^[a-zA-z][a-zA-Z0-9_]{2,9}$/', '用户名不符合格式', 0),
	        array('userName', '', '该用户名已被注册！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
	        array('email', 'require', '邮箱不能为空！'), 
			array('email', 'email', '邮箱格式不正确'), // 内置正则验证邮箱格式
			array('email', '', '该邮箱已被占用', 0, 'unique', 1), // 新增的时候email字段是否唯一
	        array('password', '/^([a-zA-Z0-9@*#]{6,22})$/', '密码格式不正确,请重新输入！', 0),
	        array('repassword', 'password', '确认密码不正确', 0, 'confirm'), // 验证确认密码是否和密码一致
		);
		
	    /**
	     * 自动完成
	     */
	    protected $_auto = array (
	        /* 登录的时候自动完成 */
	        array('password', 'md5', 3, 'function') , // 对password字段使用md5函数处理
	        array('lastUpdatedDate', 'get_client_time', 1, 'function'), // 对lastdate字段在登录的时候写入当前时间戳
	        array('lastIp', 'get_client_ip', 1, 'function'), // 对lastip字段在登录的时候写入当前登录ip地址
	    );
	}
?>