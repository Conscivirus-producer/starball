<?php
	namespace Common\Model;
	use Think\Model;
	class OrderModel extends Model {
		protected $trueTableName = 't_order';
		
	    /**
	     * 自动完成
	     */
	    protected $_auto = array (
	        /* 登录的时候自动完成 */
	        array('createdDate', 'get_client_time', 1, 'function'), // 对lastdate字段在登录的时候写入当前时间戳
	        array('updatedDate', 'get_client_time', 1, 'function'), // 对lastdate字段在登录的时候写入当前时间戳
	        array('giftPackageFee', '0'),
	    );
	}
?>