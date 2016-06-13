<?php
namespace Admin\Controller;
use Think\Controller;
class CouponController extends Controller {
    public function index() {
        $this->assign("data", D("Coupon", "Logic")->getAllCoupons());
        $this->display();
    }
	
	public function delete(){
		$map['code'] = I('code');
		$map['couponSequence'] = I('couponSequence');
		D("Coupon", "Logic")->where($map)->delete();
		$this->redirect('Coupon/index');
	}
	
	public function insertCouponPair(){
		$couponCode = I('couponCode');
        $res = array(
            "status" => "0"
        );
		$couponLogic = D("Coupon", "Logic");
		$newSequence = $couponLogic->getNewSequenceByCouponCode($couponCode);
		
		$cnyData['code'] = $couponCode;
		$cnyData['couponSequence'] = $newSequence;
		$cnyData['currency'] = 'CNY';
		$cnyData['amountBenchMark'] = I('cnyBenchmark');
		$cnyData['discount'] = I('cnyDiscount');
		$cnyData['startDate'] = I('startDate');
		$cnyData['endDate'] = I('endDate');
		$cnyResult = $couponLogic->add($cnyData);
		
		$hkdData['code'] = $couponCode;
		$hkdData['couponSequence'] = $newSequence;
		$hkdData['currency'] = 'HKD';
		$hkdData['amountBenchMark'] = I('hkdBenchmark');
		$hkdData['discount'] = I('hkdDiscount');
		$hkdData['startDate'] = I('startDate');
		$hkdData['endDate'] = I('endDate');
		$hkdResult = $couponLogic->add($hkdData);
		if($cnyResult > 0 && $hkdResult > 0){
			$res['status'] = 1;
		}
		echo json_encode($res);
	}
	
}