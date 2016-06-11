<?php
namespace Common\Logic;
use Common\Model\CouponModel;
class CouponLogic extends CouponModel{
	public function getListByCouponCode($code){
		$map['code'] = $code;
		return $this->where($map)->select();
	}
}
