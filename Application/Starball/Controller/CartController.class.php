<?php
namespace Starball\Controller;
use Think\Controller;
class CartController extends BaseController {
	public function index(){
		$this->commonProcess();
		$this->display();
	}
	
	public function delivery(){
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		$this->commonProcess();
		$shipppingAddress = D("ShippingAddress", "Logic");
		$addressList = $shipppingAddress->getAllAddress($this->getCurrentUserId());
		foreach($addressList as $record){
			if($record['default'] == 1){
				$defaultAddress = $record;
			}
		}
		if(count($addressList) == 0){
			$this->redirect('Cart/address');
		}
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
			$orderLogic->updateOrder($orderUpdate, $order['orderId']);
			$this->redirect('Cart/delivery');
		}
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
		$this->commonProcess();
		$addressId = I('addressId');
		$orderLogic = D('Order', 'Logic');
		$userId = $this->getCurrentUserId();
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		if(count($backlogOrder) > 0){
			$order = $backlogOrder[0];
			if($order['orderNumber'] == ''){
				//生成订单号,规则: 数字8(1位) + 年份最后1位，如2016最后一位6(1位) + 月份，如04(2位) + 日期，如12(2位) + 当前秒数,如59(2位) + 用户ID后2位,如87(4位) + 随机数(2位) 
				$strUtil = new \Org\Util\String();
				$orderNumber = '8'.substr(date("Ymds"), 3).substr($userId, -2).$strUtil->randString(2,1);
				$data['orderNumber'] = $orderNumber;
			}else{
				$orderNumber = $order['orderNumber'];
			}
			
			//更新订单地址信息
			$data['shippingAddress'] = $addressId;
			$orderLogic->updateOrder($data, $order['orderId']);
			
			//更新用户默认地址
			$shipppingAddress = D("ShippingAddress", "Logic");
			$shipppingAddress->unsetDefault($this->getCurrentUserId());
			$shipppingAddress->setDefault($this->getCurrentUserId(), $addressId);
			$this->redirect('Payment/index', array('orderNumber' => $orderNumber, 'addressId'=>$addressId));
		}
	}

	//增加/减少购物车的商品数量
	public function changeItemQuantity(){
		if(!$this->isLogin()){
			$this->changeItemQuantityToSession();
		}else{
			$this->changeItemQuantityToUser();
		}
		$this->redirect('Cart/index');
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
	}
	
	private function changeItemQuantityToUser(){
		$changedQuantity = I('changedQuantity');
		$changedPrice = I('changedPrice');
		$userId = $this->getCurrentUserId();
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		$order = $backlogOrder[0];
		//更新order的数量
		$data['totalItemCount'] = $order['totalItemCount'] + $changedQuantity;
		$data['totalAmount'] = round($order['totalAmount'] + $changedPrice, 2);
		$orderLogic->updateOrder($data, $order['orderId']);
		
		//更新orderitem的数量
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $order['orderId']);
		$orderItemLogic->changeQuantity($orderItem[0], I('changedQuantity'), I('changedPrice'));
	}
}