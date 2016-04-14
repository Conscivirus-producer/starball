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
		$i = 0;
		foreach($shoppingListItems as $values){
			//The key is in such format: itemId_itemSize, explode the string to get the value
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($values['itemId']);
			$values['price'] = $priceMap[$this->getCurrency()] * $values['quantity'];
			$shoppingListItems[$i] = $values;
			$newTotalPrice += $values['price'];
			$i++;
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
		$orderItems = $orderItemLogic->getOrderItemsByOrdeId($order['orderId']);
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
			$data['totalItemCount'] = $shoppingList['totalItemCount']; 
			$data['totalAmount'] = $shoppingList['totalAmount'];
			$data['userId'] = $this->getCurrentUserId(); 
			$data['status'] = 'B';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderId = $orderLogic->add();
			
			$orderItemLogic = D('OrderItem', 'Logic');
			foreach($shoppingListItems as $record){
				//创建新的记录
				$record['orderId'] = $orderId;
				$record['sizeDescription'] = D('Inventory', 'Logic')->getSizeDescriptionById($record['itemSize']);
				$record['status'] = 'B';
				$orderItemLogic->create($record);
			}			
			return;
		}
		$order = $backlogOrder[0];
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItems = $orderItemLogic->getOrderItemsByOrdeId($order['orderId']);
		//重新计算总的价格
		$newTotalPrice = $shoppingList['totalAmount'];
		$newTotalCount = $shoppingList['totalItemCount'];
		$keyArray = array();
		foreach($orderItems as $record){
			$key = $record['itemId'].'_'.$record['itemSize'];
			array_push($keyArray, $key);
		}

		foreach($shoppingListItems as $value){
			//判断记录是否已经存在
			$key = $value['itemId'].'_'.$value['itemSize'];
			if(in_array($key, $keyArray)){
				$newTotalPrice -= $value['price'];
				$newTotalCount -= $value['quantity'];
			}else{
				//如果记录不存在，创建新的记录
				$itemData['orderId'] = $order['orderId'];
				$itemData['itemId'] = current(explode('_', $key));
				$itemData['itemName'] = $value['itemName'];
				$itemData['brandName'] = $value['brandName'];
				$itemData['itemImage'] = $value['itemImage'];
				$itemData['itemColor'] = $value['itemColor'];
				$itemData['itemSize'] = end(explode('_', $key));
				$itemData['sizeDescription'] = $value['sizeDescription'];
				$itemData['price'] = $value['price'];
				$itemData['quantity'] = $value['quantity'];
				$itemData['updatedDate'] = $value['updatedDate'];
				$itemData['status'] = 'B';
				$orderItemLogic->create($itemData);
			}
		}
		
		
		$orderData['totalAmount'] = $order['totalAmount'] + $newTotalPrice;
		$orderData['totalItemCount'] = $order['totalItemCount'] + $newTotalCount;
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
	}

	protected function prepareOrderList(){
		if(session('userName') == ''){
			return;	
		}
		$orderLogic = D('Order', 'Logic');
		$map['userId'] = $this->getCurrentUserId();
		$map['status'] <> 'B';
		$result = $orderLogic->queryOrder($map);
		$orderStatus = C('ORDERSTATUS');
		$i = 0;
		foreach($result as $record){
			$record['statusDescription'] = $orderStatus[$record['status']];
			$result[$i] = $record;
			logInfo('statusDescription:'.$result[$i]['statusDescription']);
			$i++;
		}
		$this->assign('data', $result);
	}

	protected function prepareShoppingList(){
		if(session('userName') == ''){
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
			$this->assign('shoppingListItemsCount', count($shoppingListItems));
		}else{
			$userId = session('userId');
			$orderLogic = D('Order', 'Logic');
			$orderItemLogic = D('OrderItem', 'Logic');
			$backlogOrder = $orderLogic->getOrderByUserId($userId, 'B');
			$this->assign('shoppingListCount', count($backlogOrder));
			if(count($backlogOrder) > 0){
				$shoppingList = $backlogOrder[0];
				$this->assign('shoppingList', $shoppingList);
				$shoppingListItems = $orderItemLogic->getOrderItemsByOrdeId($shoppingList['orderId']);
				$this->assign('shoppingListItems', $shoppingListItems);
				$this->assign('shoppingListItemsCount', count($shoppingListItems));
			}
		}
		$currencyArray = C('CURRENCY');
		$this->assign('priceSymbol', $currencyArray[$this->getCurrency()]);
		//logInfo('priceSymbol:'.$this->get('priceSymbol'));
	}
	
	protected function commonProcess(){
		//session(null);
		header("Content-type: text/html; charset=utf-8");
		if(IS_POST){
			if(I('method') == 'register'){
				$this->register();
			}else if(I('method') == 'login'){
				$this->login();
			}else if(I('method') == 'logout'){
				logInfo('sodfjisodif');
				$this->logout();
			}
		}
		$this->prepareUserSetting();
		$this->prepareBrandList();
		$this->prepareUserMenu();
		$this->prepareShoppingList();
		$this->prepareOrderList();
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
	protected function testLogShoppingList(){
		$shoppingList = session('shoppingList');
		logInfo('shoppingList  totalItemCount:'.$shoppingList['totalItemCount'].',totalAmount:'.$shoppingList['totalAmount']);
		
		$shoppingListItems = session('shoppingListItems');
		logInfo('shoppingListItems:');
		foreach($shoppingListItems as $value){
			logInfo('itemId:'.$value['itemId'].',itemSize:'.$value['itemSize'].',itemName:'.$value['itemName'].',brandName:'.$value['brandName']
			.',itemImage:'.$value['itemImage'].',itemColor:'.$value['itemColor'].',sizeDescription:'.$value['sizeDescription'].',price:'.$value['price'].',quantity:'.$value['quantity']);
		}		
	}
}