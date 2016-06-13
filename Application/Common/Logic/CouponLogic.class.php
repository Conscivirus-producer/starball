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
	
	public function getAllCoupons(){
		return $this->order('endDate desc, code, couponSequence')->select();
	}
	
	public function getNewSequenceByCouponCode($couponCode){
		$map['code'] = $couponCode;
		$data = $this->where($map)->order('couponSequence desc')->find();
		if($data != ''){
			return $data['couponSequence'] + 1;
		}else{
			return '1';
		}
	}
}
