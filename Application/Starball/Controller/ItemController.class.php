<?php
namespace Starball\Controller;
use Think\Controller;
class ItemController extends BaseController {
	public function index(){
		$this->commonProcess();
    	$itemId = I('itemId');
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
		$this->prepareShoppingList();
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
			
			$this->updateOrderItem($orderNumber);
		} else{
			$order = $backlogOrder[0];
			$data['totalItemCount'] = $order['totalItemCount'] + 1;
			$data['totalAmount'] = $order['totalAmount'] + I('currentPrice');
			$orderLogic->updateOrder($data, $order['orderId']);
			
			$this->updateOrderItem($order['orderNumber']);
		}
	}

	private function updateOrderItem($orderNumber){
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $orderNumber);
		if(count($orderItem) > 0){
			//如果记录已经存在，数量+1
			$orderItemLogic->addQuantity($orderItem[0]);
			return;
		}
		
		//创建新的记录
		$itemData['orderNumber'] = $orderNumber;
		$itemData['itemId'] = I('itemId');
		$itemData['itemName'] = I('itemName');
		$itemData['brandName'] = I('brandName');
		$itemData['itemImage'] = I('itemImage');
		$itemData['itemColor'] = I('itemColor');
		$itemData['itemSize'] = I('itemSize');
		$itemData['sizeDescription'] = D('Inventory', 'Logic')->getSizeDescriptionById(I('itemSize'));
		$itemData['price'] = I('currentPrice');
		$itemData['quantity'] = 1;
		$itemData['status'] = 'B';
		$orderItemLogic->create($itemData);
	}

	private function addShoppingListToSession(){
		if(session('shoppingList') == ''){
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
					$record['price'] += I('currentPrice');
					$shoppingListItems[$i] = $record;
					$hasRecord = true;
					break;
				}
				$i++;
			}
			if(!$hasRecord){
				array_push($shoppingListItems, $this->processSingleOrderItemForSession());
			}
			session('shoppingListItems', $shoppingListItems);
			
			$shoppingList['totalItemCount'] += 1;
			$shoppingList['totalAmount'] += I('currentPrice');
			session('shoppingList',$shoppingList);
			
		}
		//$this->testLogShoppingList();
	}

	private function testLogShoppingList(){
		$shoppingList = session('shoppingList');
		logInfo('shoppingList  totalItemCount:'.$shoppingList['totalItemCount'].',totalAmount:'.$shoppingList['totalAmount']);
		
		$shoppingListItems = session('shoppingListItems');
		logInfo('shoppingListItems:');
		foreach($shoppingListItems as $value){
			logInfo('itemId:'.$value['itemId'].',itemSize:'.$value['itemSize'].',itemName:'.$value['itemName'].',brandName:'.$value['brandName']
			.',itemImage:'.$value['itemImage'].',itemColor:'.$value['itemColor'].',sizeDescription:'.$value['sizeDescription'].',price:'.$value['price'].',quantity:'.$value['quantity']);
		}		
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
					  'quantity'=>1);
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