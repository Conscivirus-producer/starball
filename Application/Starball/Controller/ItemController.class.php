<?php
namespace Starball\Controller;
use Think\Controller;
class ItemController extends BaseController {
	public function index(){
		$this->commonProcess();
    	$itemId = I('itemId');
		if($itemId == ''){
			$itemId = -1;
		}
		$itemData = D("Item", "Logic")->getItemById($itemId);
		$imageData = D("Image", "Logic")->getImageById($itemId);
		$priceData = D("ItemPrice", "Logic")->getPriceByItemId($itemId);
		$data = $itemData[0];
		$inventoryResult = D("Inventory", "Logic")->getInventoryByItemId($itemId);
		$inventoryData = array();
		foreach($inventoryResult as $inventory){
			$inventory['description'] = getSizeDescriptionByAge($inventory['age']);
			array_push($inventoryData, $inventory);
		}
		$this->assign('data', $data);
		$this->assign('images', $imageData);
		$this->assign('inventory', $inventoryData);
		$this->assign('prices', $priceData);
		foreach($priceData as $price){
			if(cookie('preferred_currency') == $price['currency']){
				$currencyArray = C('CURRENCY');
				$this->assign('priceSymbol', $currencyArray[$price['currency']]);
				$this->assign('currentPrice', $price['price']);
			}
		}
		$this->display();
	}
	
	public function addToShoppingList(){
		if(session('userName') == ''){
			$this->addShoppingListToSession();
		}else{
			$this->addShoppingListToUser();
		}
		$data = array(
		    'data'=>'吃饼饼',
		    'message'=>'处理成功',
		    'itemId'=>I('itemId'),
		);
		$vo = $data;
		$vo['status'] = 1;
		$this->ajaxReturn($vo, "json");
	}
	
	private function addShoppingListToUser(){
		$userId = session('userId');
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'B');
		if(count($backlogOrder) == 0){
			$strUtil = new \Org\Util\String();
			$orderNumber = $strUtil->randString(6,1);
			$data['orderNumber'] = $orderNumber;
			$data['totalItemCount'] = 1; 
			$data['totalAmount'] = I('currentPrice');
			$data['userId'] = $userId; 
			$data['status'] = 'B';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderLogic->add();
			
			$this->createOrderItem($orderNumber);
		} else{
			$order = $backlogOrder[0];
			$data['totalItemCount'] = $order['totalItemCount'] + 1;
			$data['totalAmount'] = $order['totalAmount'] + I('currentPrice');
			$orderLogic->updateOrder($data, $order['orderId']);
			
			$this->createOrderItem($order['orderNumber']);
		}
	}

	private function createOrderItem($orderNumber){
		$itemData['orderNumber'] = $orderNumber;
		$itemData['itemId'] = I('itemId');
		$itemData['itemName'] = I('itemName');
		$itemData['itemSize'] = I('itemSize');
		$itemData['price'] = I('currentPrice');
		$itemData['quantity'] = 1;
		$itemData['status'] = 'B';
		D('OrderItem')->add($itemData);
	}

	private function addShoppingListToSession(){
		$itemId = I('itemId');
		$itemSize = I('itemSize');
		if(session('shoppingList') == ''){
			//没有任何item添加到购物车
			session('shoppingList',array(I('itemId') => array(I('itemSize') => 1)));
		}else{
			$itemList = session('shoppingList');
			if(array_key_exists($itemId, $itemList)){
				//这个item已经添加了
				if(array_key_exists($itemSize, $itemList[$itemId])){
					//这个item的这个尺寸已经添加了
					$itemList[$itemId][$itemSize] += 1;
				}else{
					$itemList[$itemId][$itemSize] = 1;
				}
			}else{
				$itemList[$itemId][$itemSize] = 1;
			}
			session('shoppingList',$itemList);
		}
		
		foreach(session('shoppingList') as $itemId=>$subarray){
			foreach($subarray as $itemSize=>$quantity){
				logInfo("shoppingItemList: itemId:".$itemId.",itemSize:".$itemSize.",quantity:".$quantity);
			}
	 		foreach(session('favoriteList') as $value){
				logInfo('favoriteItemList:'.$value);
			}
		}
	}

	public function addToFavoriteList(){
		if(session('userName') == ''){
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