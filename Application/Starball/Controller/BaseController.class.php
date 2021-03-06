<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function prepareUserSetting(){
		//检查cookie，如果用户选择了记住我，那么自动加载用户信息
		if(cookie('starballkids_is_login')){
			if(cookie('starballkids_userId') != ''){
				session('starballkids_userId', cookie('starballkids_userId'));
			}
			if(cookie('starballkids_userName') != ''){
				session('starballkids_userName', cookie('starballkids_userName'));
			}
		}
		
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
			//需要根据汇率重新读取礼物费用
			$supportingData = D('SupportingData', 'Logic');
			$giftPackageFee = $supportingData->getValueByKey('GIFT_PACKAGE_PRICE_'.$this->getCurrency());
			session('giftPackageFee', $giftPackageFee);
			$this->assign('giftPackageFee', $this->getGiftPackageFee());
			$this->changeCurrency();
		}
		$this->assign('preferred_currency', cookie('preferred_currency'));
		//把currency列表放在页面上
		$currencyArray = array();
		foreach(C('CURRENCY') as $key=>$value){
			array_push($currencyArray, array('currency'=>$key));
		}
		$this->assign('currencyArray', $currencyArray);		
	}

	protected function loginActions(){
		//登录之后自动根据当前汇率重新计算用户订单的总额
		$this->updateUserShoppingListByCurrency();
		
		//并且把当前session的购物车和收藏夹加到用户下面
		$this->appendSessionToUser();
		
		//检查购物车里商品的价格,和最新的比较,如果有变动,更新过来
		$this->checkShoppingListPrice();
		
	}
	
	protected function prepareSupportingData(){
		$supportingData = D('SupportingData', 'Logic');
		$giftPackageFee = $supportingData->getValueByKey('GIFT_PACKAGE_PRICE_'.$this->getCurrency());
		session('giftPackageFee', $giftPackageFee);
		$this->assign('giftPackageFee', $this->getGiftPackageFee());
	}
	
	private function changeCurrency(){
		if(!$this->isLogin()){
			$this->updateSessionShoppingListByCurrency();
			$this->updateSessionFavoriteByCurrency();
		}else{
			$this->updateUserShoppingListByCurrency();
			$this->updateUserFavoriteByCurrency();
		}
	}
	
	private function updateSessionFavoriteByCurrency(){
		$favoriteList = session('favoriteList');
		if($favoriteList == ''){
			return;
		}
		$i = 0;
		foreach($favoriteList as $record){
			$defaultPrice = D('ItemPrice', 'Logic')->getDefaultPrice($record['itemId'], $this->getCurrency());
			logInfo('$defaultPrice:'.$defaultPrice);
			$record['price'] = $defaultPrice;
			$favoriteList[$i] = $record;
		}
		session('favoriteList', $favoriteList);
	}
	
	private function updateUserFavoriteByCurrency(){
		
	}
	
	private function updateSessionShoppingListByCurrency(){
		$shoppingList = session('shoppingList');
		if($shoppingList == ''){
			return;
		}
		$shoppingListItems = session('shoppingListItems');
		$newTotalPrice = 0;
		$i = 0;
		foreach($shoppingListItems as $values){
			//The key is in such format: itemId_itemSize, explode the string to get the value
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($values['itemId'], $this->getCurrency());
			$values['price'] = $priceMap[$values['itemSize']] * $values['quantity'];
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
			$priceMap = D("ItemPrice", "Logic")->getPriceMap($record['itemId'], $this->getCurrency());
			$data['price'] = $priceMap[$record['itemSize']] * $record['quantity'];
			$orderItemLogic->updateOrderItem($data, $record['id']);
			$newTotalPrice += $data['price'];
		}
		
		$orderData['totalAmount'] = $newTotalPrice;
		//如果现有礼物包装费用为0，则保持0
		$orderData['giftPackageFee'] = $order['giftPackageFee'] == 0 ? 0 : $this->getGiftPackageFee();
		$orderData['shippingFee'] = $this->calculateShippingFee($orderData['totalAmount']);
		$orderData['totalFee'] = $orderData['totalAmount'] + $orderData['giftPackageFee'] + $orderData['shippingFee'];
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
		
		//如果有coupon code,则看不同汇率下面相同的couponCode和sequence,计算discount
		if($order['couponId'] != 0){
			$couponLogic = D('Coupon', 'Logic');
			$previousCurrencyCoupon = $couponLogic->getByCouponId($order['couponId']);
			$newCurrencyCoupon = $couponLogic->getCorrelateCouponByCurrency($previousCurrencyCoupon, $this->getCurrency());
			
			$orderCouponData['couponId'] = $newCurrencyCoupon['couponId'];
			$orderCouponData['totalAmount'] = $orderData['totalAmount'] - $newCurrencyCoupon['discount'];
			$orderCouponData['totalFee'] = $orderData['totalFee'] - $newCurrencyCoupon['discount'];
			$orderLogic->updateOrder($orderCouponData, $order['orderId']);
		}
	}
	
	protected function prepareBrandList(){
		$this->assign('brandList', D('Brand')->select());
	}
	
	protected function prepareUserMenu(){
		$itemLogic = D("Item", "Logic");
		foreach (C('USERTYPE') as $key => $value){
			$this->assign($key.'MenuBrand', $itemLogic->getBrandNameListByGrade($value[0], $value[1]));				
			$this->assign($key.'MenuCategory', $itemLogic->getCategoryNameByGrade($value[0], $value[1]));
			$this->assign($key.'MenuAge', $itemLogic->getAgeListByGrade($value[0], $value[1]));
		}
		$this->assign('shoesMenuBrand', $itemLogic->getBrandForShoes());
		$this->assign('shoesMenuCategory', $itemLogic->getCategoryForShoes());
		$this->assign('shoesMenuAge', D('Inventory', 'Logic')->getFootSizeListForShoes());
	}
	
	protected function prepareBoutiqueMenu(){
		$map["t_hotitem.type"] = array("EQ", "S");
		$boutiqueList = D("Hotitem")->where($map)->limit(3)->select();
		$this->assign("boutiqueMenu", $boutiqueList);
	}
	
	private function appendSessionToUser(){
		$this->appendSessionShoppingListToUser();
		$this->appendSessionFavoriteToUser();
	}
	
	private function appendSessionFavoriteToUser(){
		$favoriteList = session('favoriteList');
		if($favoriteList == ''){
			return;
		}
		$userFavoriteList = D('FavoriteItem', 'Logic')->getFavoriteList($this->getCurrentUserId());
		$itemIdArray = array();
		foreach($userFavoriteList as $record){
			array_push($itemIdArray, $record['itemId']);
		}
		foreach($favoriteList as $record){
			if(in_array($record['itemId'], $itemIdArray)){
				continue;
			}
			$record['userId'] = $this->getCurrentUserId();
			$record['createdDate'] = $record['updatedDate'];
			D('FavoriteItem', 'Logic')->add($record);
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
			//之前没有任何订单记录
			$data['totalItemCount'] = $shoppingList['totalItemCount'];
			$data['totalAmount'] = $shoppingList['totalAmount'];
			$data['shippingFee'] = $this->calculateShippingFee($data['totalAmount']);
			$data['totalFee'] = $data['totalAmount'] + $data['shippingFee'];
			$data['userId'] = $this->getCurrentUserId(); 
			$data['status'] = 'N';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderId = $orderLogic->add();
			
			$orderItemLogic = D('OrderItem', 'Logic');
			foreach($shoppingListItems as $record){
				//创建新的记录
				if($record['quantity'] == 0){
					continue;
				}
				$record['orderId'] = $orderId;
				$record['sizeDescription'] = D('Inventory', 'Logic')->getSizeDescriptionById($record['itemSize']);
				$record['status'] = 'N';
				$orderItemLogic->create($record);
			}			
			return;
		}
		//之前已有订单
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
				if($value['quantity'] == 0){
					continue;
				}
				$itemData['orderId'] = $order['orderId'];
				$itemData['itemId'] = current(explode('_', $key));
				$itemData['itemName'] = $value['itemName'];
				$itemData['brandId'] = $value['brandId'];
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
		$orderData['shippingFee'] = $this->calculateShippingFee($orderData['totalAmount']);
		$orderData['totalFee'] = $orderData['totalAmount'] + $order['giftPackageFee'] + $orderData['shippingFee'];
		$orderData['totalItemCount'] = $order['totalItemCount'] + $newTotalCount;
		$orderData['currency'] = $this->getCurrency();
		$orderLogic->updateOrder($orderData, $order['orderId']);
	}

	private function checkShoppingListPrice(){
		//检查商品价格是否有变化
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->getCurrentOutstandingOrder($this->getCurrentUserId(),'N');
		$orderChangedAmount = 0;
		//用来存放变化之后的价格变量
		if($order != ''){
			$orderId = $order['orderId'];
			$orderItemLogic = D('OrderItem', 'Logic');
			$orderItemList = $orderItemLogic->getOrderItemsByOrdeId($orderId);
			foreach($orderItemList as $orderItem){
				$priceMap = D('ItemPrice', 'Logic')->getPriceMap($orderItem['itemId'], $order['currency']);
				$itemPrice = $priceMap[$orderItem['itemSize']];
				$latestPrice = $itemPrice * $orderItem['quantity'];
				//如果价格有变动
				if($latestPrice != $orderItem['price']){
					$orderItemData['price'] = $latestPrice;
					$orderItemLogic->updateOrderItem($orderItemData, $orderItem['id']);
					$orderChangedAmount += $latestPrice - $orderItem['price'];
				}
			}
		}
		//如果加起来的价格发生了改变
		if($orderChangedAmount != 0){
			$data['totalAmount'] = $order['totalAmount'] + $orderChangedAmount;
			$data['totalFee'] = $order['totalFee'] + $orderChangedAmount;
			$orderLogic->updateOrder($data, $orderId);
		}
		
		//如果购物车有使用优惠券,检查优惠券是否过期了
		if($order['couponId'] != 0){
			$coupon = D('Coupon', 'Logic')->getByCouponId($order['couponId']);
			if(strtotime($coupon['startDate']) > strtotime("now") || strtotime($coupon['endDate']) < strtotime("now")){
				$this->cancelCouponCore();
				$this->redirect('Cart/index?message='.L('coupon').$coupon['code'].L('expired'));
			}
		}
	}
	
	protected function cancelCouponCore(){
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->getCurrentOutstandingOrder($this->getCurrentUserId(),'N');
		$coupon = D('Coupon', 'Logic')->getByCouponId($order['couponId']);
		
		$order['couponId'] = 0;
		$order['totalAmount'] = $order['totalAmount'] + $coupon['discount'];
		$order['totalFee'] = $order['totalFee'] + $coupon['discount'];
		$orderLogic->updateOrder($order, $order['orderId']);
	}

	protected function prepareShoppingList(){
		if(!$this->isLogin()){
			$shoppingList = session('shoppingList');
			if($shoppingList != ''){
				$shoppingList['totalFee'] = $shoppingList['totalAmount'];
				$shoppingList['shippingFee'] = 0;
				$shoppingList['giftPackageFee'] = 0;
				$this->assign('shoppingListCount', $shoppingList['totalItemCount']);
				$shoppingListItems = session('shoppingListItems');
				$this->assign('shoppingList', $shoppingList);
				$this->assign('shoppingListItems', $shoppingListItems);
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
			}else{
				$this->assign('shoppingListCount', 0);
			}
		}
		$currencyArray = C('CURRENCY');
		$this->assign('priceSymbol', $currencyArray[$this->getCurrency()]);
	}

	protected function prepareFavoriteList(){
		if(!$this->isLogin()){
			$favoriteList = session('favoriteList');
		}else{
			$userId = $this->getCurrentUserId();
			$favoriteList = D('FavoriteItem', 'Logic')->getFavoriteList($userId);
		}
		$this->assign('favoriteList', $favoriteList);
		$this->assign('favoriteListCount', count($favoriteList));
	}
	
	protected function commonProcess(){
		//session(null);
		if(isMobile() && C('DISABLE_MOBILE') == 'true'){
			$this->redirect('Home/maintenance');
		}
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
		$this->prepareSupportingData();
		$this->prepareBrandList();
		$this->prepareUserMenu();
		$this->prepareBoutiqueMenu();
		$this->prepareShoppingList();
		$this->prepareFavoriteList();
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
		//注册成功后把当前session的购物车加到用户下面
		$this->appendSessionToUser();
		//从哪里跳到登录页面，跳回去
		if(session('fromAction') != ''){
			$actionArray = C('FROM_ACTION');
			$actionDetail = $actionArray[session('fromAction')];
			$this->redirect($actionDetail['url'], $actionDetail['params']);
		}
	}
	
	public function logout(){
		session(null);
		cookie('starballkids_is_login', false, C('COOKIE_DURATION'));
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
			
			//如果选择了记住我,把用户信息存入cookie,否则清除掉cookie
			if(I('rememberMe') == 'on'){
				cookie('starballkids_is_login', true, C('COOKIE_DURATION'));
				cookie('starballkids_userId', session('starballkids_userId'), C('COOKIE_DURATION'));
				cookie('starballkids_userName', session('starballkids_userName'), C('COOKIE_DURATION'));
			}else{
				cookie('starballkids_is_login', '');
				cookie('starballkids_userId', '');
				cookie('starballkids_userName', '');				
			}
			
			//登录之后需要做的行为
			$this->loginActions();

			//$this->assign('userName', $result['userName']);
		} else{
			$this->error("用户名密码不正确");
		}
		//从哪里跳到登录页面，跳回去
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
	
	protected function getGiftPackageFee(){
		return session('giftPackageFee');
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
	
	protected function parseAddressListCode($addressList){
		$i=0;
		foreach($addressList as $address){                          
			$addressList[$i] = parseAddressCode($address);
			$i++;
		}
		return $addressList;
	}
	
	protected function calculateShippingFee($totalAmount){
		$defaultAddress = D('ShippingAddress', 'Logic')->getDefaultAddress($this->getCurrentUserId());
		$shippingFeeSetting = C('SHIPPING_FEE_SETTING');
		$exchangeRate = C('EXCHANGE_RATE_HKD_TO_CNY');
		if($defaultAddress['country'] == 'hk'){
			//如果当前地址是香港
			if($defaultAddress['deliveryType'] == 0){
				//地铁站自取
				return 0;
			}else if($defaultAddress['deliveryType'] == 1){
				//商品价格达到免邮标准
				if($totalAmount - $shippingFeeSetting['FREE_BENCHMARK_'.$this->getCurrency()] > 0){
					return 0;
				}
				$totalShippingFee = $shippingFeeSetting['HK_DEFAULT_COST'];
				//这个是基于港币的结果，如果当前币种是人民币，转化成港币
				return $this->getCurrency() == 'HKD' ? $totalShippingFee : round($totalShippingFee * $exchangeRate, 2);
			}
		}else if($defaultAddress['country'] == 'cn'){
			//商品价格达到免邮标准
			if($totalAmount - $shippingFeeSetting['FREE_BENCHMARK_'.$this->getCurrency()] > 0){
				return 0;
			}
			
			//如果是国内地址,根据首重,续重的规则来.特殊商品有额外费用的设置
			//计算重量，还有额外费用总和
			$backlogOrder = D('Order', 'Logic')->getOrderByUserId($this->getCurrentUserId(), 'N');
			$shoppingList = $backlogOrder[0];
			$shoppingListItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($shoppingList['orderId']);
			$extraShippingFee = 0;
			$totalWeight = 0;
			foreach($shoppingListItems as $orderItem){
				$item = D('Item', 'Logic')->findById($orderItem['itemId']);
				/*--------Hard code for testing start----------------*/
				if(C('IS_TEST') == 'true' && $item['itemId'] == 158){
					return '0';
				}
				/*--------Hard code for testing end----------------*/
				$extraShippingFee += $item['extraShippingFee'] * $orderItem['quantity'];
				$totalWeight += $item['weight'] * $orderItem['quantity'];
			}
			$provinceList = C('CHINA_PROVINCE_LIST');
			$firstKgFee = $provinceList[$defaultAddress['province']][1];
			$extendKgFee = $provinceList[$defaultAddress['province']][2];
			$totalShippingFee = 0;
			if($totalWeight - 1 < 0){
				$totalShippingFee = $extraShippingFee + $firstKgFee;
			}else{
				$extendCount = ceil(($totalWeight - 1)/$shippingFeeSetting['EXTENDED_WEIGHT_BENCHMARK']);
				$totalShippingFee = $extraShippingFee + $firstKgFee + $extendCount * $extendKgFee;
			}
			//这个是基于人民币的结果，如果当前币种是港币，转化成人民币
			return $this->getCurrency() == 'CNY' ? $totalShippingFee : round($totalShippingFee / $exchangeRate, 2);
		}
		return 0;
	}
	
	protected function checkOrderItemsInventory($orderId){
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItems = $orderItemLogic->getOrderItemsByOrdeId($orderId);
		$inadequateInventoryItems = array();
		foreach($orderItems as $orderItem){
			if(!D('Inventory', 'Logic')->isInventoryAvailable($orderItem['itemSize'], $orderItem['quantity'])){
				$inadequateInventoryItems = array('itemId' => $orderItem['itemId'], 'itemSize'=>$orderItem['itemSize']);
			}
		}
		return $inadequateInventoryItems;
	}
	
	public function loginByWechatinMobile(){
		$openid = "";
		if (isset($_GET['code'])){
			//1.获取用户openid
		    $code = $_GET['code'];
		    $access_token_get_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C("WECHAT_APP_ID")."&secret=".C("WECHAT_APP_SECRET")."&code=".$code."&grant_type=authorization_code";
		    $access_token_json = file_get_contents($access_token_get_url); 
		    $json_obj = json_decode($access_token_json,true);
			$openid = $json_obj["openid"];
		    
			//2,获取access_token
			$access_token_get_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C("WECHAT_APP_ID")."&secret=".C("WECHAT_APP_SECRET");
			$access_token_json = file_get_contents($access_token_get_url); 
			$json_obj = json_decode($access_token_json,true);
			$access_token = $json_obj["access_token"];
			//3,再获取基本信息
			$basic_information_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
			$basic_information_json = file_get_contents($basic_information_url);
			$json_obj = json_decode($basic_information_json,true); 
			$nickname = $json_obj["nickname"];
			
            $userInfo['type'] = 'WEIXIN';
            $userInfo['name'] = $nickname;
			$userInfo['nick'] = $nickname;
			$userInfo['head'] = $json_obj["headimgurl"];
			
			$this->checkExistingUserInformation($openid, $userInfo);
		}
	}
	
	public function loginFromSocialMedia($type = null){
    	if(C('IS_DEV') == 'true'){
			$weiboId = "123451114";
			$user_info['type'] = 'SINA';
			$user_info['name'] = 'super002';
			$user_info['nick'] = '老王';
			$user_info['head'] = 'http://tva2.sinaimg.cn/crop.0.0.180.180.180/68302600jw1e8qgp5bmzyj2050050aa8.jpg';
			$this->checkExistingUserInformation($weiboId, $user_info);
    	} else if(is_weixin_browser() && $type == 'weixin'){// 
    		//如果是在微信浏览器点击通过微信登录
    		$url = createOauthUrlForCode('http://'.$_SERVER['HTTP_HOST'].U('Home/loginByWechatinMobile'));
    		header('Location: '.$url);
    	}else{
			empty($type) && $this->error('参数错误');
	
			//加载ThinkOauth类并实例化一个对象
			import("Org.ThinkSDK.ThinkOauth");
			$sns  = \ThinkOauth::getInstance($type);
			//跳转到授权页面
			redirect($sns->getRequestCodeURL());
		}
	}
	
	//授权回调地址
	public function callback($type = null, $code = null){
		(empty($type) || empty($code)) && $this->error('参数错误');
		
		//加载ThinkOauth类并实例化一个对象
		import("Org.ThinkSDK.ThinkOauth");
		$sns  = \ThinkOauth::getInstance($type);

		//腾讯微博需传递的额外参数
		$extend = null;
		if($type == 'tencent'){
			$extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
		}

		//请妥善保管这里获取到的Token信息，方便以后API调用
		//调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
		//如： $qq = ThinkOauth::getInstance('qq', $token);
		$token = $sns->getAccessToken($code , $extend);

		//获取当前登录用户信息
		if(is_array($token)){
			$user_info = A('Type', 'Event')->$type($token);
			$this->checkExistingUserInformation($token['openid'], $user_info);
		}
	}
	
	protected function checkExistingUserInformation($openid, $user_info){
		$socialMediaLogic = D('UserSocialMedia', 'Logic');
		$existingUser = $socialMediaLogic->findByOpenId($openid);
		if($existingUser == ''){
			//创建用户数据，用户名为临时的
			$userData['lastUpdatedDate'] = date("Y-m-d H:i:s" ,time());
			$userId = D('User', 'Logic')->add($userData);
			
			$user_info['userId'] = $userId;
			$user_info['openid'] = $openid;
			$user_info['lastIp'] = get_client_ip();
			D('UserSocialMedia', 'Logic')->add($user_info);
			session('starballkids_userId', $userId);
			session('starballkids_userName', $user_info['name']);
		}else{
			session('starballkids_userId', $existingUser['userId']);
			$user = D('User', 'Logic')->getUserInformationByUserId($existingUser['userId']);
			$userName = '';
			//如果还没有设置用户名,就用社交媒体的用户名
			$userName = $user['userName'] != '' ? $user['userName'] : $existingUser['name'];
			session('starballkids_userName', $userName);
			session('starballkids_email', $user['email']);
		}
		session('starballkids_social_openid', $openid);
		
		//如果选择了记住我,把用户信息存入cookie
		if(I('rememberMe') == 'on'){
			cookie('starballkids_is_login', true, C('COOKIE_DURATION'));
			cookie('starballkids_userId', session('starballkids_userId'), C('COOKIE_DURATION'));
			cookie('starballkids_userName', session('starballkids_userName'), C('COOKIE_DURATION'));
		}
		
		//登录之后需要做的行为
		$this->loginActions();
		
		if($user == '' || $user['userName'] == ''){
			//如果用户还没有绑定现有帐号
			$this->redirect('User/index');
		}
		//从哪里跳到登录页面，跳回去
		if(session('fromAction') != ''){
			$actionArray = C('FROM_ACTION');
			$actionDetail = $actionArray[session('fromAction')];
			$this->redirect($actionDetail['url'], $actionDetail['params']);
		}else{
			//否则直接跳主页
			$this->redirect('Home/index');
		}
	}
	
}
