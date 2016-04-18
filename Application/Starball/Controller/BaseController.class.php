<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function prepareUserSetting(){
		//如果当前用户的默认语言不在支持的语言列表里,那么设为默认语言
		$langList = C('LANG_LIST');
		$currencyLang = strtolower(cookie('think_language'));
		if(false == stripos($langList,$currencyLang)){
			cookie('think_language',C('DEFAULT_LANG'),3600);
			$currencyLang = strtolower(cookie('think_language'));
		}
		logInfo('current_lang:'.cookie('think_language'));
		logInfo('current currency:'.$this->getCurrency());
		if($this->getCurrency() == ''){
			//根据当前语言自动设置currency
			$langCurrencyMap = C('LANG_CURRENCY');
			cookie('preferred_currency',$langCurrencyMap[$currencyLang],3600);
			logInfo('current currency:'.$this->getCurrency());
		}
		//如果货币发生了变化,那么把用户购物车里的金额按照货币转化
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
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'N');
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
		$backlogOrder = $orderLogic->getOrderByUserId($this->getCurrentUserId(), 'N');
		if(count($backlogOrder) == 0){
			$data['totalItemCount'] = $shoppingList['totalItemCount']; 
			$data['totalAmount'] = $shoppingList['totalAmount'];
			$data['userId'] = $this->getCurrentUserId(); 
			$data['status'] = 'N';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderId = $orderLogic->add();
			
			$orderItemLogic = D('OrderItem', 'Logic');
			foreach($shoppingListItems as $record){
				//创建新的记录
				$record['orderId'] = $orderId;
				$record['sizeDescription'] = D('Inventory', 'Logic')->getSizeDescriptionById($record['itemSize']);
				$record['status'] = 'N';
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
				$itemData['status'] = 'N';
				$orderItemLogic->create($itemData);
			}
		}
		
		$orderData['totalAmount'] = $order['totalAmount'] + $newTotalPrice;
		$orderData['totalItemCount'] = $order['totalItemCount'] + $newTotalCount;
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
	}

	protected function prepareOrderList(){
		if(!$this->isLogin()){
			return;	
		}
		$orderLogic = D('Order', 'Logic');
		$map['userId'] = $this->getCurrentUserId();
		$map['status'] <> 'N';
		$result = $orderLogic->queryOrder($map);
		$orderStatus = C('ORDERSTATUS');
		$i = 0;
		foreach($result as $record){
			$record['statusDescription'] = $orderStatus[$record['status']];
			$result[$i] = $record;
			$i++;
		}
		$this->assign('data', $result);
	}

	protected function prepareShoppingList(){
		if(!$this->isLogin()){
			$shoppingList = session('shoppingList');
			if($shoppingList != ''){
				$this->assign('shoppingListCount', $shoppingList['totalItemCount']);
				$shoppingListItems = session('shoppingListItems');
				$this->assign('shoppingList', $shoppingList);
				$this->assign('shoppingListItems', $shoppingListItems);
				$this->assign('shoppingListItemsCount', count($shoppingListItems));
			}else{
				$this->assign('shoppingListCount', 0);
			}
		}else{
			$userId = $this->getCurrentUserId();
			$orderLogic = D('Order', 'Logic');
			$orderItemLogic = D('OrderItem', 'Logic');
			$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
			if(count($backlogOrder) > 0){
				$shoppingList = $backlogOrder[0];
				$this->assign('shoppingListCount', $shoppingList['totalItemCount']);
				$this->assign('shoppingList', $shoppingList);
				$shoppingListItems = $orderItemLogic->getOrderItemsByOrdeId($shoppingList['orderId']);
				$this->assign('shoppingListItems', $shoppingListItems);
				$this->assign('shoppingListItemsCount', count($shoppingListItems));
			}else{
				$this->assign('shoppingListCount', 0);
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
		session('starballkids_userId', $userId);
		session('starballkids_userName', I('userName'));
		session('starballkids_email', I('email'));
	}
	
	private function logout(){
		session(null);
		$this->redirect('Home/index');
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
			session('starballkids_userId', $result['userId']);
			session('starballkids_email', $result['email']);
			session('starballkids_userName', $result['userName']);
			session('starballkids_lastLoginDate', $result['lastUpdatedDate']);
			session('starballkids_lastLoginIp', $result['lastIp']);
			
			//登录之后自动根据当前汇率重新计算用户订单的总额
			$this->updateUserShoppingListByCurrency();
			
			//并且把当前session的购物车加到用户下面
			$this->appendSessionShoppingListToUser();
			//$this->assign('userName', $result['userName']);
		} else{
			$this->error("用户名密码不正确");
		}
		//从哪里跳到登录页面，跳回去
		logInfo('fromAction:'.session('fromAction'));
		if(session('fromAction') != ''){
			$actionArray = C('FROM_ACTION');
			$actionDetail = $actionArray[session('fromAction')];
			$this->redirect($actionDetail['url'], $actionDetail['params']);
		}
	}

	protected function isLogin(){
		return session('starballkids_userName') != '';
	}

	protected function getCurrentUserId(){
		return session("starballkids_userId");	
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