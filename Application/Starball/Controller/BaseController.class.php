<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function prepareUserSetting(){
		if(cookie('think_language') == 'zh-cn' && cookie('preferred_currency') == ''){
			cookie('preferred_currency','CNY',3600);
		}
		if(I('currency') != '' && I('currency') != $this->getCurrency()){
			cookie('preferred_currency',I('currency'),3600);
			$this->updateUserShoppingListByCurrency();
		}
		$this->assign('preferred_currency', cookie('preferred_currency'));		
	}
	
	private function updateUserShoppingListByCurrency(){
		if(!$this->isLogin()){
			return;
		}
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'B');
		if(count($backlogOrder) == 0){
			return;
		}
		$order = $backlogOrder[0];
		
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItems = $orderItemLogic->getOrderItemsByOrdeNumber($order['orderNumber']);
		//重新计算总的价格
		$newTotalPrice = 0;
		foreach($orderItems as $record){
			//更新每个item的记录
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($record['itemId']);
			$data['price'] = $priceMap[$this->getCurrency()];
			$orderItemLogic->updateOrderItem($data, $record['id']);
			$newTotalPrice += $data['price'] * $record['quantity'];
		}
		
		$orderData['totalAmount'] = $newTotalPrice;
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
	}
	
	protected function prepareBrandList(){
		$this->assign('brandList', D('Brand')->select());
	}
	
	protected function prepareUserMenu(){
		$itemLogic = D("Item", "Logic");
		foreach (C('USERTYPE') as $key => $value){
			$this->assign($key.'MenuBrand', $itemLogic->getBrandNameListByGrade($value[0], $value[1]));				
			$this->assign($key.'MenuCategory', $itemLogic->getCategoryNameByGrade($value[0], $value[1]));
		}
	}
	
	private function appendSessionShoppingListToUser(){
		$shoppingList = session('shoppingList');
		$favoriteList = session('favoriteList');
		$totalQuantity = 0;
		$totalPrice = 0;
		
		foreach($shoppingList as $itemIds => $subarray){
			$itemData = D("Item", "Logic")->getItemById($itemId);
			$imageData = D("Image", "Logic")->getImageById($itemId);
			$priceData = D("ItemPrice", "Logic")->getPriceByItemId($itemId);
			foreach($subarray as  $itemSize=>$quantity){
				$totalQuantity += $quantity;		
				
			}
		}		
	}
	
	protected function prepareShoppingList(){
		
		if(session('userName') == ''){
			//$this->assign('shoppingList',$shoppingList);
			//$this->assign('favoriteList',$favoriteList);
		}else{
			$userId = session('userId');
			$orderLogic = D('Order', 'Logic');
			$orderItemLogic = D('OrderItem', 'Logic');
			$backlogOrder = $orderLogic->getOrderByUserId($userId, 'B');
			if(count($backlogOrder) == 0){
				$this->assign('emptyshoppinglist', 'true');
			}else{
				$order = $backlogOrder[0];
				$this->assign('shoppingList', $order);
				$orderItems = $orderItemLogic->getOrderItemsByOrdeNumber($order['orderNumber']);
				$this->assign('shoppingListItems', $orderItems);
			}
		}
		//log testing
		/*foreach(session('shoppingList') as $itemId=>$subarray){
			foreach($subarray as $itemSize=>$quantity){
				logInfo("shoppingItemList: itemId:".$itemId.",itemSize:".$itemSize.",quantity:".$quantity);
			}
	 		foreach(session('favoriteList') as $value){
				logInfo('favoriteItemList:'.$value);
			}
		}*/
	}
	
	protected function commonProcess(){
		$this->prepareUserSetting();
		$this->prepareBrandList();
		$this->prepareUserMenu();
		$this->prepareShoppingList();
		if(IS_POST){
			if(I('method') == 'register'){
				$this->register();
			}else if(I('method') == 'login'){
				$this->login();
			}else if(I('method') == 'logout'){
				$this->logout();
			}
		}
	}
	
	//abstract protected function pageDisplay();	
	
	private function register(){
		$user = D("User");
		if(!$data = $user->create()){
            // 防止输出中文乱码
            header("Content-type: text/html; charset=utf-8");
            exit($user->getError());
		}
		$userId = $user->add($data);
		session('userId', $userId);
		session('userName', I('userName'));
		session('email', I('email'));
	}
	
	private function logout(){
		session(null);
	}
	
	private function login(){
		$login = D('Login');
		
		if(!$data = $login->create()){
		    header("Content-type: text/html; charset=utf-8");
            exit($login->getError());
		}
		
		$where['userName'] = $data['userName'];
		$where['email'] = $data['userName'];
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		
		$map['password'] = $data['password'];
		$result = $login->where($map)->find();
		if($result){
			session('userId', $result['userId']);
			session('email', $result['email']);
			session('userName', $result['userName']);
			session('lastDate', $result['lastUpdatedDate']);
			session('lastIp', $result['lastIp']);
			//$this->assign('userName', $result['userName']);
		} else{
			$this->error("用户名密码不正确");
		}
	}

	protected function isLogin(){
		return session("userName") != '';
	}

	protected function getCurrentUserId(){
		return session("userId");	
	}	
	
	protected function getCurrency(){
		return cookie('preferred_currency');
	}
}