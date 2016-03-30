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
		$data = $itemData[0];
		$inventoryResult = D("Inventory", "Logic")->getInventoryByItemId($itemId);
		$inventoryData = array();
		foreach($inventoryResult as $inventory){
			$inventory['description'] = $this->getSizeDescriptionByAge($inventory['age']);
			array_push($inventoryData, $inventory);
		}
		$this->assign('data', $data);
		$this->assign('images', $imageData);
		$this->assign('inventory', $inventoryData);
		$this->display();
	}
	
	public function addToShoppingList(){
		if(session('userName') == ''){
			$this->addShoppingListToSession();
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
	}
	
}