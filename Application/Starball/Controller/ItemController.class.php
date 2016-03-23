<?php
namespace Starball\Controller;
use Think\Controller;
class ItemController extends BaseController {
    protected function pageDisplay(){
    	$itemId = I('itemId');
		if($itemId == ''){
			$itemId = -1;
		}
		$itemLogic = D("Item", "Logic");
		$itemData = $itemLogic->getItemById($itemId);
		$imageLogic = D("Image", "Logic");
		$imageData = $imageLogic->getImageById($itemId);
		$data = $itemData[0];
		//logInfo('dataresult: '.count($imageData));
		$this->assign('data', $data);
		$this->assign('images', $imageData);
    }
}