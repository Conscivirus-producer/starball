<?php
namespace Starball\Controller;
use Think\Controller;
class ItemController extends BaseController {
	public function index($itemId){
		$this->commonProcess();
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
		$outputHtml = $this->prepareNewShoppingListHtml();
		$data = array(
		    'html'=>$outputHtml,
		    'message'=>'处理成功',
		);
		$vo = $data;
		$vo['status'] = 1;
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
			$url = U('Starball/Item/index','itemId='.$record['itemId']);
			$tab = $tab."<li><a href='".$url."' class='item-panier'> <img alt='".$record['itemName']."' src='".$record['itemImage']."?imageView2/1/w/100/h/100/q/100' class='item-photo-adjustor'> 
						<span class='content-item-panier'> <span class='title-item-panier'>".$record['brandName']."</span> <span class='subtitle-item-panier'>".$record['itemName']."</span> 
						<span class='yu-size-container'> <span class='' style='text-transform: none;'> 尺码： <span class='am-sans-serif'style='font-weight:bold;'>".$record['sizeDescription']."</span> </span> 
						<span class='' style='float:right'> 数量： <span class=''>".$record['quantity']."</span> </span> <span class='am-sans-serif yu-item-price'> <span> ".$this->get('priceSymbol')."&nbsp;".$record['price'].
						" </span> </span> </span> </span> </a></li>";
			$i++;
		}
		$tab = $tab."<li style='margin:0;'><div id='' class='yu-total-price-panier'><span class=''>".L('totalAmount')."： </span><span class='am-sans-serif value-total-price' >".$this->get('priceSymbol')."&nbsp; ".$shoppingList['totalAmount']."</span></div></li>";
		$tab = $tab."<li class='am-divider'></li><button type='button' id='myShoppingCart' class='am-btn am-btn-default yu-button-333 yu-check-button-adjustor'>查看我的购物袋</button>";
		return $tab;
	}
	
	private function addShoppingListToUser(){
		$userId = session('userId');
		$orderLogic = D('Order', 'Logic');
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'N');
		if(count($backlogOrder) == 0){
			$data['totalItemCount'] = 1; 
			$data['totalAmount'] = I('currentPrice');
			$data['userId'] = $userId; 
			$data['status'] = 'N';
			$data['currency'] = $this->getCurrency();
			$orderLogic->create($data);
			$orderId = $orderLogic->add();
			
			$this->updateOrderItem($orderId);
		} else{
			$order = $backlogOrder[0];
			$data['totalItemCount'] = $order['totalItemCount'] + 1;
			$data['totalAmount'] = $order['totalAmount'] + I('currentPrice');
			$orderLogic->updateOrder($data, $order['orderId']);
			
			$this->updateOrderItem($order['orderId']);
		}
	}

	private function updateOrderItem($orderId){
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getExistingOrderItem(I('itemId'), I('itemSize'), $orderId);
		if(count($orderItem) > 0){
			//如果记录已经存在，数量+1
			$orderItem[0]['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$orderItemLogic->changeQuantity($orderItem[0], 'add');
			return;
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
	}

	protected function testLogShoppingList(){
		$shoppingList = session('shoppingList');
		logInfo('shoppingList  totalItemCount:'.$shoppingList['totalItemCount'].',totalAmount:'.$shoppingList['totalAmount']);
		
		$shoppingListItems = session('shoppingListItems');
		logInfo('shoppingListItems:');
		foreach($shoppingListItems as $value){
			logInfo('itemId:'.$value['itemId'].',itemSize:'.$value['itemSize'].',itemName:'.$value['itemName'].',brandName:'.$value['brandName']
			.',itemImage:'.$value['itemImage'].',itemColor:'.$value['itemColor'].',sizeDescription:'.$value['sizeDescription']
			.',price:'.$value['price'].',quantity:'.$value['quantity'].',updatedDate:'.$value['updatedDate']);
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
					  'quantity'=>1,
					  'updatedDate'=>date("Y-m-d H:i:s" ,time()));
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