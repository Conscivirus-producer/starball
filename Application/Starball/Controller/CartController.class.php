<?php
namespace Starball\Controller;
use Think\Controller;
class CartController extends BaseController {
	public function index(){
		$this->commonProcess();
		if(I('message') != ''){
			$this->assign('message', urldecode(I('message')));
		}
		if(I('itemId') != ''){
			$item = D('Item', 'Logic')->findById(I('itemId'));
			$itemName = $item['name'];
			$sizeDescription = D('Inventory', 'Logic')->getSizeDescriptionById(I('itemSize'));
			$this->assign('message', $itemName.' 的尺码:'.$sizeDescription.' 库存不足');
		}
		$order = $this->get('shoppingList');
		if($order['couponId'] != 0){
			$coupon = D('Coupon', 'Logic')->getByCouponId($order['couponId']);
			$this->assign('couponCode', $coupon['code']);
			$this->assign('totalAmountOrigin', $order['totalAmount'] + $coupon['discount']);
		}
		$this->display();
	}
	
	public function delivery(){
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->getCurrentOutstandingOrder($this->getCurrentUserId(), 'N');
		//检查库存
		$inadequateInventoryItems = $this->checkOrderItemsInventory($order['orderId']);
		if(count($inadequateInventoryItems) > 0){
			$this->redirect('Cart/index',array('itemId'=>$inadequateInventoryItems['itemId'], 'itemSize'=>$inadequateInventoryItems['itemSize']));
		}
		
		$data = array();
		//如果需要礼品包装,把包装费用加到总费用里
		if(I('isGiftPackage') != ''){
			$data['giftPackageFee'] = I('isGiftPackage') == 'true' ? $this->getGiftPackageFee() : 0;
			$data['totalFee'] = $order['totalAmount'] + $order['shippingFee'] + $data['giftPackageFee'];
		}
		if(I('addtionalGreetings') != ''){
			$data['addtionalGreetings'] = I('addtionalGreetings');
		}
		if(!empty($data)){
			$orderLogic->updateOrder($data, $order['orderId']);
		}
		
		$this->commonProcess();	
		$shipppingAddress = D("ShippingAddress", "Logic");
		$addressList = $shipppingAddress->getAllAddress($this->getCurrentUserId());
		if(count($addressList) == 0){
			$this->redirect('Cart/address');
		}
		$addressList = $this->parseAddressListCode($addressList);
		$this->assign('addressList', $addressList);
		$this->display();
	}
	
	public function address(){
		$this->commonProcess();
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		$this->commonProcess();
		$shipppingAddress = D("ShippingAddress", "Logic");
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->getCurrentOutstandingOrder($this->getCurrentUserId(), 'N');
		if(IS_POST){
			if(!$data = $shipppingAddress->create()){
	            header("Content-type: text/html; charset=utf-8");
	            exit($user->getError());	
			}
			//把其它address设成非default
			$shipppingAddress->unsetDefault($this->getCurrentUserId());
			//把addressId存入order
			$data['userId'] = $this->getCurrentUserId();
			$orderUpdate['shippingAddress'] = $shipppingAddress->add($data);
			$orderUpdate['shippingFee'] = $this->calculateShippingFee($order['totalAmount']);
			$orderUpdate['totalFee'] = $order['totalAmount'] + $order['giftPackageFee'] + $orderUpdate['shippingFee'];
			$orderLogic->updateOrder($orderUpdate, $order['orderId']);
			$this->redirect('Cart/delivery');
		}
		$provinceList = array();
		foreach(C('CHINA_PROVINCE_LIST') as $key=>$value){
			$tmpArray = array('code'=>$key, 'display'=>$value[0]);
			array_push($provinceList, $tmpArray);
		}
		$this->assign('provinceList', $provinceList);
		$countryArray = array();
		foreach(C('COUNTRY_LIST') as $key=>$value){
			$tmpArray = array('code'=>$key, 'display'=>L($value));
			array_push($countryArray, $tmpArray);
		}
		$this->assign('countryList', $countryArray);
		$this->display();
	}
	
	public function test(){
		$this->commonProcess();
		$this->display();
	}
	
	public function submitOrder(){
		if(!$this->isLogin()){
			$this->redirect('Home/register');
		}
		$this->commonProcess();
		$orderLogic = D('Order', 'Logic');
		$userId = $this->getCurrentUserId();
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		if(count($backlogOrder) > 0){
			$order = $backlogOrder[0];
			//如果没有地址,用默认地址
			if($order['shippingAddress'] == 0){
				$defaultAddress = D("ShippingAddress", "Logic")->getDefaultAddress($userId);
				$data['shippingAddress'] = $defaultAddress['addressId'];
			}
			//检查库存
			$inadequateInventoryItems = $this->checkOrderItemsInventory($order['orderId']);
			if(count($inadequateInventoryItems) > 0){
				$this->redirect('Cart/index',array('itemId'=>$inadequateInventoryItems['itemId'], 'itemSize'=>$inadequateInventoryItems['itemSize']));
			}

			if($order['orderNumber'] == ''){
				//生成订单号,规则: 数字8(1位) + 年份最后1位，如2016最后一位6(1位) + 月份，如04(2位) + 日期，如12(2位) + 当前秒数,如59(2位) + 用户ID后2位,如87(2位) + 随机数(2位) 
				$strUtil = new \Org\Util\String();
				$orderNumber = '8'.substr(date("Ymds"), 3).substr($userId, -2).$strUtil->randString(2,1);
				$data['orderNumber'] = $orderNumber;
			}else{
				$orderNumber = $order['orderNumber'];
			}
			$data['orderDate'] = date("Y-m-d H:i:s" ,time());
			$orderLogic->updateOrder($data, $order['orderId']);
			$this->redirect('Payment/index', array('orderNumber' => $orderNumber));
		}
	}

	public function changeAddress(){
		//Ajax call,改变地址,计算运费,不更新DB,只是给用户参考,用户点击提交订单后才会更新数据库
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'N');
		$order = $backlogOrder[0];
		$addressId = I('addressId');
		//更新用户默认地址
		$shipppingAddress = D("ShippingAddress", "Logic");
		$shipppingAddress->unsetDefault($this->getCurrentUserId());
		$shipppingAddress->setDefault($addressId);

		//计算运费,根据最新的地址
		$shippingFee = $this->calculateShippingFee($order['totalAmount']);
					
		//更新订单信息,运费,总费用,地址
		$data['shippingAddress'] = $addressId;
		$data['shippingFee'] = $shippingFee;
		$data['totalFee'] = $order['totalAmount'] + $order['giftPackageFee'] + $shippingFee;
		$orderLogic->updateOrder($data, $order['orderId']);
		
		$data = array(
		    'shippingFee'=>$data['shippingFee'],
		    'totalFee'=>$data['totalFee'],
		    'message'=>'处理成功',
		);
		$vo = $data;
		$vo['status'] = 1;
		$this->ajaxReturn($vo, "json");
	}

	//增加/减少购物车的商品数量
	public function changeItemQuantity(){
		$result = true;
		if(!$this->isLogin()){
			$result = $this->changeItemQuantityToSession();
		}else{
			$result = $this->changeItemQuantityToUser();
		}
		if($result){
			$this->redirect('Cart/index');
		}else{
			$this->redirect('Cart/index?message='.L('addQuantityFailed'));
		}
	}
	
	private function changeItemQuantityToSession(){
		$shoppingList = session('shoppingList');
		$shoppingListItems = session('shoppingListItems');
		$changedQuantity = I('changedQuantity');
		$changedPrice = I('changedPrice');
		$i = 0;
		foreach($shoppingListItems as $record){
			if($record['itemId'] == I('itemId') && $record['itemSize'] == I('itemSize')){
				$record['quantity'] += $changedQuantity;
				if((I('changedQuantity') > 0) && !D('Inventory', 'Logic')->isInventoryAvailable($record['itemSize'], $record['quantity'])){
					//如果库存不足
					return false;
				}
				$record['price'] += $changedPrice;
				$shoppingListItems[$i] = $record;
				break;
			}
			$i++;
		}
		
		$shoppingList['totalItemCount'] += $changedQuantity;
		$shoppingList['totalAmount'] = round($shoppingList['totalAmount'] + $changedPrice, 2);
		session('shoppingListItems', $shoppingListItems);
		session('shoppingList',$shoppingList);
		return true;
	}
	
	private function changeItemQuantityToUser(){
		$changedQuantity = I('changedQuantity');
		$changedPrice = I('changedPrice');
		$userId = $this->getCurrentUserId();
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		$order = $backlogOrder[0];
		//更新orderitem的数量
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $order['orderId']);
		if((I('changedQuantity') > 0) && !D('Inventory', 'Logic')->isInventoryAvailable(I('itemSize'), $orderItem[0]['quantity'] + I('changedQuantity'))){
			//如果库存不足
			return false;
		}
		$orderItemLogic->changeQuantity($orderItem[0], I('changedQuantity'), I('changedPrice'));
		
		//更新order的数量
		$data['totalItemCount'] = $order['totalItemCount'] + $changedQuantity;
		$data['totalAmount'] = round($order['totalAmount'] + $changedPrice, 2);
		$data['shippingFee'] = $this->calculateShippingFee($data['totalAmount']);
		$data['totalFee'] = $data['totalAmount'] + $data['shippingFee'] + $order['giftPackageFee'];
		$orderLogic->updateOrder($data, $order['orderId']);
		return true;
	}

	public function userCoupon(){
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		$this->commonProcess();
		$couponCode = I('couponCode');
		if($couponCode == ''){
			$this->redirect('Cart/index?message='.L('couponEmpty'));
		}
		$couponList = D('Coupon', 'Logic')->getActiveCode($couponCode, $this->getCurrency());
		if(empty($couponList)){
			$this->redirect('Cart/index?message='.L('couponNotExist'));				
		}
		if(strtotime($couponList[0]['startDate']) > strtotime("now") || strtotime($couponList[0]['endDate']) < strtotime("now")){
			$this->redirect('Cart/index?message='.L('coupon').$couponCode.L('expired'));
		}
		$orderLogic = D('Order', 'Logic');
		$order = $this->get('shoppingList');
		if($order['couponCode'] != 0){
			$this->redirect('Cart/index?message='.L('orderAlreadyHaveCoupon'));
		}
		$hasMatchRecord = false;
		$totalAmount = $order['totalAmount'];
		foreach($couponList as $coupon){
			if(($order['totalAmount'] - $coupon['amountBenchMark']) >= 0){
				//满足条件，使用优惠券
				$order['couponId'] = $coupon['couponId'];
				$order['totalAmount'] = $order['totalAmount'] - $coupon['discount'];
				$order['totalFee'] = $order['totalFee'] - $coupon['discount'];
				$hasMatchRecord = true;
				break;
			}
		}
		
		//如果所购买的金额不够任何满减条款
		if(!$hasMatchRecord){
			$this->redirect('Cart/index?message='.L('insufficientBuyingRecord'));
		}
		
		$orderLogic->updateOrder($order, $order['orderId']);
		$this->redirect('Cart/index');
	}

	public function cancelCoupon(){
		$this->cancelCouponCore();
		$this->redirect('Cart/index');
	}
}