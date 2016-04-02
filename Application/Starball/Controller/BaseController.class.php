<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function prepareUserSetting(){
		if(cookie('think_language') == 'zh-CN' && cookie('preferred_currency') == ''){
			cookie('preferred_currency','CNY',3600);
		}
		if(I('currency') != '' && I('currency') != $this->getCurrency()){
			cookie('preferred_currency',I('currency'),3600);
			$this->updateShoppingListByCurrency();
		}
		$this->assign('preferred_currency', cookie('preferred_currency'));		
	}
	
	private function updateShoppingListByCurrency(){
		if(!$this->isLogin()){
			$this->updateSessionShoppingListByCurrency();
		}else{
			$this->updateUserShoppingListByCurrency();
		}
	}
	
	private function updateSessionShoppingListByCurrency(){
		$shoppingList = session('shoppingList');
		$shoppingListItems = session('shoppingListItems');
		$newTotalPrice = 0;
		foreach($shoppingListItems as $key => $values){
			//The key is in such format: itemId_itemSize, explode the string to get the value
			$array = explode('_', $key);
			$itemId = current($array);
			$itemSize = end($array);
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($itemId);
			$values['price'] = $priceMap[$this->getCurrency()] * $values['quantity'];
			$shoppingListItems[$key] = $values;
			$newTotalPrice += $values['price'];
		}
		session('shoppingListItems', $shoppingListItems);
		
		$shoppingList['totalAmount'] = $newTotalPrice;
		session('shoppingList', $shoppingList);
	}
	
	private function updateUserShoppingListByCurrency(){
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'B');
		if(count($backlogOrder) == 0){
			//No order found.
			return;
		}
		$order = $backlogOrder[0];
		if($order['currency'] == $this->getCurrency()){
			//If currency is same with the existing return.
			return;
		}
		
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
		$shoppingListItems = session('shoppingListItems');
		if($shoppingList == '' || $shoppingListItems == ''){
			return;
		}
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'B');
		if(count($backlogOrder) == 0){
			//No order found.
			return;
		}
		$order = $backlogOrder[0];
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItems = $orderItemLogic->getOrderItemsByOrdeNumber($order['orderNumber']);
		//重新计算总的价格
		$newTotalPrice = $shoppingList['totalAmount'];
		$newTotalCount = $shoppingList['totalItemCount'];
		foreach($orderItems as $record){
			$key = $record['itemId'].'_'.$record['itemSize'];
			//该记录不存在时，才需要存入数据库
			if(array_key_exists($key, $shoppingListItems)){
				$newTotalPrice -= $shoppingListItems[$key]['price'];
				$newTotalCount -= $shoppingListItems[$key]['quantity'];
				unset($shoppingListItems[$key]);
			}
			//更新每个item的记录
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($record['itemId']);
			$data['price'] = $priceMap[$this->getCurrency()];
			$orderItemLogic->updateOrderItem($data, $record['id']);
			$newTotalPrice += $data['price'] * $record['quantity'];
		}
		
		foreach($shoppingListItems as $key => $value){
			//创建新的记录
			$itemData['orderNumber'] = $order['orderNumber'];
			$itemData['itemId'] = current(explode('_', $key));
			$itemData['itemName'] = $value['itemName'];
			$itemData['brandName'] = $value['brandName'];
			$itemData['itemImage'] = $value['itemImage'];
			$itemData['itemColor'] = $value['itemColor'];
			$itemData['itemSize'] = end(explode('_', $key));
			$itemData['sizeDescription'] = $value['sizeDescription'];
			$itemData['price'] = $value['price'];
			$itemData['quantity'] = $value['quantity'];
			$itemData['status'] = 'B';
			$orderItemLogic->create($itemData);
		}
		
		
		$orderData['totalAmount'] += $newTotalPrice;
		$orderData['totalItemCount'] += $newTotalCount;
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
	}
	
	protected function prepareShoppingList(){
		if(session('userName') == ''){
			//$this->assign('shoppingList',$shoppingList);
			//$this->assign('favoriteList',$favoriteList);
			$shoppingList = session('shoppingList');
			if($shoppingList != '' && $shoppingList['totalItemCount'] > 0){
				$this->assign('shoppingListCount', 1);
			}else{
				$this->assign('shoppingListCount', 0);
				return;
			}
			$shoppingListItems = session('shoppingListItems');
			$this->assign('shoppingList', $shoppingList);
			$this->assign('shoppingListItems', $shoppingListItems);
		}else{
			$userId = session('userId');
			$orderLogic = D('Order', 'Logic');
			$orderItemLogic = D('OrderItem', 'Logic');
			$backlogOrder = $orderLogic->getOrderByUserId($userId, 'B');
			$this->assign('shoppingListCount', count($backlogOrder));
			if(count($backlogOrder) > 0){
				$order = $backlogOrder[0];
				$this->assign('shoppingList', $order);
				$orderItems = $orderItemLogic->getOrderItemsByOrdeNumber($order['orderNumber']);
				$this->assign('shoppingListItems', $orderItems);
				$currencyArray = C('CURRENCY');
				$this->assign('priceSymbol', $currencyArray[$order['currency']]);
			}
		}
	}
	
	protected function commonProcess(){
		if(IS_POST){
			if(I('method') == 'register'){
				$this->register();
			}else if(I('method') == 'login'){
				$this->login();
			}else if(I('method') == 'logout'){
				$this->logout();
			}
		}
		$this->prepareUserSetting();
		$this->prepareBrandList();
		$this->prepareUserMenu();
		$this->prepareShoppingList();
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
			
			//登录之后自动根据当前汇率重新计算用户订单的总额
			$this->updateUserShoppingListByCurrency();
			
			//并且把当前session的购物车加到用户下面
			$this->appendSessionShoppingListToUser();
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