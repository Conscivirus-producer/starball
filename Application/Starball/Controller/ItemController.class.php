<?php
namespace Starball\Controller;
use Think\Controller;
class ItemController extends BaseController {
	public function index($itemId){
		$this->commonProcess();
		$itemData = D("Item", "Logic")->getItemWithBrandAndCategoryById($itemId);
		$imageData = D("Image", "Logic")->getImageById($itemId);
		$inventoryResult = D("Inventory", "Logic")->getInventoryAndPriceByItemId($itemId, $this->getCurrency());
		
		$inventoryData = array();
		$currencyArray = C('CURRENCY');
		foreach($inventoryResult as $inventory){
			if($itemData['type'] == '1'){
				$inventory['description'] = $this->getSizeDescriptionAndPriceByAge($inventory['age'], $inventory['price'], $currencyArray[$this->getCurrency()], $itemData['isAvailable']);
			}else if($itemData['type'] == '2'){
				$inventory['description'] = $this->getShoeSizeDescription($inventory['footSize'], $inventory['price'], $currencyArray[$this->getCurrency()]);
			}
			array_push($inventoryData, $inventory);
		}
		if($itemData['type'] == '1'){
			$inventoryData = array_customized_sort($inventoryData, 'age',SORT_ASC, SORT_NUMERIC);
		}else if($itemData['type'] == '2'){
			$inventoryData = array_customized_sort($inventoryData, 'footSize',SORT_ASC, SORT_NUMERIC);
		}
		$this->assign('inventory', $inventoryData);
		$this->assign('inventoryjson', json_encode($inventoryData));
		$this->assign('data', $itemData);
		$this->assign('images', $imageData);
		$this->assign('defaultPrice', $inventoryData[0]['price']);
		$this->display();
	}

	private function getShoeSizeDescription($footSize, $price, $currency){
		$result = $footSize.L('footsizeunit').' - '.$currency.' '.$price;
		return $result;
	}

	private function getSizeDescriptionAndPriceByAge($age, $price, $currency, $isAvailable){
		$sizeArray = C('ITEMSIZE');
		$result = '';
		if(strpos($age, ',') <= 0){
			$result = $sizeArray[$age][0].'  ('.$sizeArray[$age][1].' - '.$sizeArray[$age][2].'cm)'.' - '.$currency.' '.$price;
		}else{
			$numberArray = explode(',', $age);
			$numberArray = array_splice($numberArray,0,-1);
			$startAge = current($numberArray);
			$endAge = end($numberArray);
			if($endAge == '24'){
			//如果是圴码
				return $sizeArray[$startAge][0].' - '.$currency.' '.$price;
			}else if($endAge == $startAge){
				$result = $sizeArray[$startAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)'.' - '.$currency.' '.$price;
			}else{
				$result = $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)'.' - '.$currency.' '.$price;
			}
		}
		if($isAvailable == 2){
			$result = $result.'	'.L('comingsoonalert');
		}
		return $result;
	}
	
	public function addToShoppingList(){
		$result = true;
		if(!$this->isLogin()){
			$result = $this->addShoppingListToSession();
		}else{
			$result = $this->addShoppingListToUser();
		}
		$vo = array();
		if($result){
			$vo['html'] = $this->prepareNewShoppingListHtml();
			$vo['message'] = '添加成功';
			$vo['status'] = 1;
		}else{
			$vo['html'] = '';
			$vo['message'] = '该尺码库存不足';
			$vo['status'] = 0;			
		}
		$this->ajaxReturn($vo, "json");
	}
	
	public function confirmSubscription(){
		$vo['status'] = 0;
		$id = D('ItemSubscription', 'Logic')->createRecord(I('subscriptionEmail'), I('itemId'));
		if($id > 0){
			$vo['status'] = 1;	
		}
		$this->ajaxReturn($vo, "json");
	}
	
	private function prepareNewShoppingListHtml(){
		$this->prepareShoppingList();
		$shoppingList = $this->get('shoppingList');
		$shoppingListItems = $this->get('shoppingListItems');
		$tab = "<div class='scroll-item-list'><strong>".L('recentShoppingList')."</strong></div>";
		$tab = $tab."<li class='am-divider'></li>";
		$i = 0;
		foreach($shoppingListItems as $record){
			if($i > 2){
				break;
			}
			$i++;
			if($record['quantity'] == 0){
				continue;
			}
			$url = U('Starball/Item/index','itemId='.$record['itemId']);
			$tab = $tab."<li><a href='".$url."' class='item-panier'> <img alt='".$record['itemName']."' src='".$record['itemImage']."?imageView2/1/w/100/h/100/q/100' class='item-photo-adjustor'> 
						<span class='content-item-panier'> <span class='title-item-panier'>".$record['brandName']."</span> <span class='subtitle-item-panier'>".$record['itemName']."</span> 
						<span class='yu-size-container'> <span class='' style='text-transform: none;'> 尺码： <span class='am-sans-serif'style='font-weight:bold;'>".$record['sizeDescription']."</span> </span> 
						<span class='' style='float:right'> 数量： <span class=''>".$record['quantity']."</span> </span> <span class='am-sans-serif yu-item-price'> <span> ".$this->get('priceSymbol')."&nbsp;".$record['price'].
						" </span> </span> </span> </span> </a></li>";
		}
		$tab = $tab."<li style='margin:0;'><div id='' class='yu-total-price-panier'><span class=''>".L('totalAmount')."： </span><span class='am-sans-serif value-total-price' >".$this->get('priceSymbol')."&nbsp; ".$shoppingList['totalAmount']."</span></div></li>";
		$tab = $tab."<li class='am-divider'></li><button type='button' id='myShoppingCart' class='am-btn am-btn-default yu-button-333 yu-check-button-adjustor'>查看我的购物袋</button>";
		return $tab;
	}
	
	private function addShoppingListToUser(){
		$userId = $this->getCurrentUserId();
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		if(count($backlogOrder) == 0){
			if(!D('Inventory', 'Logic')->isInventoryAvailable(I('itemSize'), 1)){
				//如果库存不足
				return false;
			}
			$data['totalItemCount'] = 1; 
			$data['totalAmount'] = I('currentPrice');
			$data['shippingFee'] = $this->calculateShippingFee($data['totalAmount']);
			$data['totalFee'] = $data['totalAmount'] + $data['shippingFee'];
			$data['userId'] = $userId; 
			$data['status'] = 'N';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderId = $orderLogic->add();
			
			$this->updateOrderItem($orderId);
		} else{
			$order = $backlogOrder[0];
			if(!$this->updateOrderItem($order['orderId'])){
				return false;
			}
			
			$data['totalItemCount'] = $order['totalItemCount'] + 1;
			$data['totalAmount'] = $order['totalAmount'] + I('currentPrice');
			$data['shippingFee'] = $this->calculateShippingFee($data['totalAmount']);
			$data['totalFee'] = $data['totalAmount'] + $data['shippingFee'] + $order['giftPackageFee'];
			$orderLogic->updateOrder($data, $order['orderId']);
		}
		return true;
	}

	private function updateOrderItem($orderId){
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $orderId);
		if(count($orderItem) > 0){
			if(!D('Inventory', 'Logic')->isInventoryAvailable(I('itemSize'), $orderItem[0]['quantity'] + 1)){
				//如果库存不足
				return false;
			}
			//如果记录已经存在，数量+1
			$orderItem[0]['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$orderItemLogic->changeQuantity($orderItem[0], 1, I('currentPrice'));
			return true;
		}
		
		if(!D('Inventory', 'Logic')->isInventoryAvailable(I('itemSize'), 1)){
			//如果库存不足
			return false;
		}
		//创建新的记录
		$itemData['orderId'] = $orderId;
		$itemData['itemId'] = I('itemId');
		$itemData['itemName'] = I('itemName');
		$itemData['brandName'] = I('brandName');
		$itemData['itemImage'] = I('itemImage');
		$itemData['itemColor'] = I('itemColor');
		$itemData['itemSize'] = I('itemSize');
		$itemData['sizeDescription'] = D('Inventory', 'Logic')->getSizeDescriptionById(I('itemSize'));
		$itemData['price'] = I('currentPrice');
		$itemData['quantity'] = 1;
		$itemData['status'] = 'N';
		$orderItemLogic->create($itemData);
		return true;
	}
	
	private function isInventoryAvailable($quantity){
		$inventoryId = I('itemSize');
		return D('Inventory', 'Logic')->isInventoryAvailable($inventoryId, $quantity);
	}

	private function addShoppingListToSession(){
		if(session('shoppingList') == ''){
			if(!D('Inventory', 'Logic')->isInventoryAvailable(I('itemSize'), 1)){
				//如果库存不足
				return false;
			}
			//没有任何item添加到购物车
			session('shoppingList',array('totalItemCount' => '1', 'totalAmount'=>I('currentPrice')));
			session('shoppingListItems', array($this->processSingleOrderItemForSession()));
		}else{
			$shoppingList = session('shoppingList');
			$shoppingListItems = session('shoppingListItems');
			$hasRecord = false;
			$i = 0;
			foreach($shoppingListItems as $record){
				if($record['itemId'] == I('itemId') && $record['itemSize'] == I('itemSize')){
					$record['quantity'] += 1;
					if(!D('Inventory', 'Logic')->isInventoryAvailable($record['itemSize'], $record['quantity'])){
						//如果库存不足
						return false;
					}
					$record['price'] += I('currentPrice');
					$record['updatedDate'] = date("Y-m-d H:i:s" ,time());
					$shoppingListItems[$i] = $record;
					$hasRecord = true;
					break;
				}
				$i++;
			}
			if(!$hasRecord){
				array_push($shoppingListItems, $this->processSingleOrderItemForSession());
			}
			//Sort $shoppingListItems, based on updatedDate
			session('shoppingListItems', array_customized_sort($shoppingListItems, 'updatedDate',SORT_DESC, SORT_STRING));
			
			$shoppingList['totalItemCount'] += 1;
			$shoppingList['totalAmount'] += I('currentPrice');
			session('shoppingList',$shoppingList);
		}
		//$this->testLogShoppingList();
		return true;
	}

	private function processSingleOrderItemForSession(){
		return array('itemId'=>I('itemId'),
					  'itemSize'=>I('itemSize'),
					  'itemName'=>I('itemName'), 
					  'brandName'=>I('brandName'), 
					  'itemImage'=>I('itemImage'),
					  'itemColor'=>I('itemColor'),
					  'sizeDescription'=>D('Inventory', 'Logic')->getSizeDescriptionById(I('itemSize')),
					  'price'=>I('currentPrice'),
					  'quantity'=>1,
					  'updatedDate'=>date("Y-m-d H:i:s" ,time()));
	}

	public function addToFavoriteList(){
		if(!$this->isLogin()){
			$this->addFavoriteListToSession();
		}else{
			
		}
		$data = array(
		    'data'=>'吃饼饼',
		    'message'=>'处理成功',
		);
		$vo = $data;
		$vo['status'] = 1;
		$this->ajaxReturn($vo, "json");
	}

	private function addFavoriteListToSession(){
		$itemId = I('itemId');
		if(session('favoriteList') == ''){
			session('favoriteList',array($itemId));
		}else{
			$itemList = session('favoriteList');
			if(!in_array($itemId, $itemList)){
				array_push($itemList, $itemId);
				session('favoriteList',$itemList);
			}
		}
	}	
}