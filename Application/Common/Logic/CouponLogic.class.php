<?php
namespace Common\Logic;
use Common\Model\CouponModel;
class CouponLogic extends CouponModel{
	public function getActiveCode($code, $currency){
		$map['code'] = $code;
		$map['currency'] = $currency;
		return $this->where($map)->order('amountBenchMark desc')->select();
	}
	
	public function getByCouponId($couponId){
		$map['couponId'] = $couponId;
		return $this->where($map)->find();
	}
	
	public function getCorrelateCouponByCurrency($previousCurrencyCoupon, $currentCurrency){
		$map['code'] = $previousCurrencyCoupon['code'];
		$map['couponSequence'] = $previousCurrencyCoupon['couponSequence'];
		$map['currency'] = $currentCurrency;
		return $this->where($map)->find();
	}
}
