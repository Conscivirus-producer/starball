<?php
namespace Starball\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
    	$this->display();
    }
	
	public function showList($by,$byValue, $p=1, $filter=""){
		$filters = Array();
		if($filter != ""){
			$filters = explode(",", $filter);
		}
		logInfo(json_encode($filters));
		if($by == "time"){
			$map["DateDiff(now(),lastUpdatedDate)"] = array('ELT', $byValue); 
		}elseif($by == "brand"){
			
		}
		$itemList = D('Item')->field('t_item.*, img.imageSmall, img.imageMiddle, img.imageBig')
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
		$this->assign('filters',json_encode($filters));
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		$this->assign('category', $category);
		$this->display('index');
	}

	public function ajaxList(){
		if($by == "time"){
			$map["DateDiff(now(),lastUpdatedDate)"] = array('ELT', $byValue); 
		}elseif($by == "brand"){
			
		}
		$itemList = D('Item')->field('t_item.*, img.imageSmall, img.imageMiddle, img.imageBig')
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
		$this->assign('filters',json_encode($filters));
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		$this->assign('category', $category);
		$this->display('index');
	}
}
