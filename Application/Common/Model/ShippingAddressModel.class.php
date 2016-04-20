<?php
	namespace Common\Model;
	use Think\Model;
	class ShippingAddressModel extends Model {
		protected $trueTableName = 't_shippingaddress';
		
	    /**
	     * 自动完成
	     */
	    protected $_auto = array (
	        /* 登录的时候自动完成 */
	    );
	}
?>