<?php
namespace Starball\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
    	$this->display();
    }
	
	public function showList($by,$byValue, $p=1){
		$filters = Array();
		$categories = I('get.categories');
		$genders = I('get.genders');
		if($categories != ""){
			$categoriesChecked = explode(",", $categories);
			$map["categoryId"] = array('IN',$categories );
		}
		if($genders != ""){
			$gendersChecked = explode(",", $genders);
			$map["gender"] = array('IN',$genders );
		}
		logInfo(json_encode($categoriesChecked));
		if($by == "time"){
			$map["DateDiff(now(),lastUpdatedDate)"] = array('ELT', $byValue); 
		}elseif($by == "brand"){
			
		}
		$itemList = D('Item')->field('t_item.*, img.image')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->order('brandId desc,categoryId desc, t_item.itemId desc')
							 ->page($p.',12')
							 ->select();
		$count = D('Item')->where($map)->count();
		$category = D('Category')->getCategory();
		$Page = new \Think\Page($count,12);
		$show = $Page->show();
		$this->assign('by',$by);
		$this->assign('byValue',$byValue);
		$this->assign('categoriesChecked',json_encode($categoriesChecked));
		$this->assign('gendersChecked',json_encode($gendersChecked));
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		$this->assign('category', $category);
		$this->display('index');
	}
}
