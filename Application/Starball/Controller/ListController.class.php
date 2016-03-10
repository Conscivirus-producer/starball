<?php
namespace Starball\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
    	$this->display();
    }
	
	public function byTimeRange($range, $page=1, $filter=array()){
		$itemList = D('Item')->field('t_item.*, img.imageSmall, img.imageMiddle, img.imageBig')
							 ->where('DateDiff(now(),lastUpdatedDate)<='.$range)
							 ->join('t_image img ON img.itemId = t_item.itemId')
							 ->order('brandId desc,categoryId desc')
							 ->page($page.',10')
							 ->select();
		logInfo(json_encode($itemList));
		$this->assign('itemList',$itemList);
		$this->display('index');
	}
}
