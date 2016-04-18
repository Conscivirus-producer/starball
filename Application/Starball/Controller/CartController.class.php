<?php
namespace Starball\Controller;
use Think\Controller;
class CartController extends BaseController {
	public function index(){
		$this->commonProcess();
		$this->display();
	}
	
	public function delivery(){
		$this->commonProcess();
		$shipppingAddress = D("ShippingAddress", "Logic");
		$existingAddress=array();
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->getCurrentOutstandingOrder($this->getCurrentUserId(), 'N');
		if($order['shippingAddress'] != ''){
			$existingAddress = $shipppingAddress->findExsitingAddress($order['shippingAddress']);
		}
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		if(IS_POST){
			if(!$data = $shipppingAddress->create()){
	            header("Content-type: text/html; charset=utf-8");
	            exit($user->getError());	
			}
			if($order['shippingAddress'] != 0){
				$shipppingAddress->updateAddress($data, $order['shippingAddress']);
			}else{
				$orderUpdate['shippingAddress'] = $shipppingAddress->add($data);
				$orderLogic->updateOrder($orderUpdate, $order['orderId']);
			}
			$this->redirect('Cart/submitOrder');
		}
		$this->assign('data', $existingAddress);
		$this->display();
	}
	
	public function test(){
		$this->commonProcess();
		$this->display();
	}
	
	public function submitOrder(){
		$this->commonProcess();
		$orderLogic = D('Order', 'Logic');
		$userId = $this->getCurrentUserId();
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		if(count($backlogOrder) > 0){
			$order = $backlogOrder[0];
			if($order['orderNumber'] != ''){
				//订单已提交，订单号已经存在
				$this->redirect('Payment/index', array('orderNumber' => $order['orderNumber']));
			}
			//生成订单号,规则: 数字8(1位) + 年份最后1位，如2016最后一位6(1位) + 月份，如04(2位) + 日期，如12(2位) + 当前秒数,如59(2位) + 用户ID后2位,如87(4位) + 随机数(2位) 
			$strUtil = new \Org\Util\String();
			$orderNumber = '8'.substr(date("Ymds"), 3).substr($userId, -2).$strUtil->randString(2,1);
			$data['orderNumber'] = $orderNumber;
			//$data['status'] = 'N';
			$orderLogic->updateOrder($data, $order['orderId']);
			$this->redirect('Payment/index', array('orderNumber' => $orderNumber));
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
		$changedQuantity = I('action') == 'add' ? 1 : -1;
		$changedPrice = I('action') == 'add' ? I('price') : -I('price');
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
		$shoppingList['totalAmount'] += $changedPrice;
		session('shoppingListItems', $shoppingListItems);
		session('shoppingList',$shoppingList);
	}
	
	private function changeItemQuantityToUser(){
		$changedQuantity = I('action') == 'add' ? 1 : -1;
		$changedPrice = I('action') == 'add' ? I('price') : -I('price');
		$userId = $this->getCurrentUserId();
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		$order = $backlogOrder[0];
		//更新order的数量
		$data['totalItemCount'] = $order['totalItemCount'] + $changedQuantity;
		$data['totalAmount'] = $order['totalAmount'] + $changedPrice;
		$orderLogic->updateOrder($data, $order['orderId']);
		
		//更新orderitem的数量
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $order['orderId']);
		$orderItemLogic->changeQuantity($orderItem[0], I('action'));
	}
}