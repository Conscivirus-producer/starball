<?php
namespace Starball\Controller;
use Think\Controller;
class ListController extends BaseController {
	
	public function showList($by,$byValue, $p=1){
		$this->commonProcess();
		
		//get params
		$brands = I('get.brands');
		$categories = I('get.categories');
		$genders = I('get.genders');
		$ages = I('get.ages');
		$colors = I('get.colors');
		$seasons = I('get.seasons');
		
		//check page entry
		if($by == "time"){
			$map["DateDiff(now(),lastUpdatedDate)"] = array('ELT', $byValue); 
		}elseif($by == "brand"){
			$map["t_item.brandId"] = array('EQ', $byValue);
		}
		
		//get filters
		if($brands != ""){
			$brandsChecked = explode(",", $brands);
			$map["t_item.brandId"] = array('IN',$brands );
		}
		if($genders != ""){
			$gendersChecked = explode(",", $genders);
			$map["t_item.gender"] = array('IN',$genders );
		}
		if($ages != ""){
			$agesChecked = explode(",", $ages);
			
		}
		if($colors != ""){
			$colorsChecked = explode(",", $colors);
			$map["t_item.color"] = array('IN',$colors );
		}
		if($seasons != ""){
			$seasonsChecked = explode(",", $seasons);
			$map["t_item.season"] = array('IN',$seasons );
		}
		if($categories != ""){
			$categoriesChecked = explode(",", $categories);
			$map["t_item.categoryId"] = array('IN',$categories );
		}
		
		//get item list and paging
		$itemList = D('Item')->field('t_item.*, img.image')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->order('brandId desc,categoryId desc, t_item.itemId desc')
							 ->page($p.',12')
							 ->select();
		$count = D('Item')->where($map)->count();
		$Page = new \Think\Page($count,12);
		$show = $Page->show();					 
		//refresh filters
		// unset($map["t_item.categoryId"]);
		// $category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId')->group('t_item.categoryId')->order('categoryId desc')->select();
		// unset($map["t_item.brandId"]);
		// $brand = D('Item')->field('distinct t_item.brandId, brd.brand Name, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		// $gender = D('Item')->field('distinct t_item.gender')->where($map)->select();
		// $color = D('Item')->field('distinct t_item.color')->where($map)->select();
		// $season = D('Item')->field('distinct t_item.season')->where($map)->select();
		$filterArray = array_keys(I('get.'));
		if(in_array("categories", $filterArray)){
			// unset($map["t_item.categoryId"]);
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId')->group('t_item.categoryId')->order('categoryId desc')->select();
			// $map["t_item.categoryId"] = array('IN',$categories );
		}else{
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId')->group('t_item.categoryId')->order('categoryId desc')->select();
		}
		if(in_array("brands", $filterArray)){
			// unset($map["t_item.brandId"]);
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
			// $map["t_item.brandId"] = array('IN',$brands );
		}else{
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		}
		if(in_array("genders", $filterArray)){
			// unset($map["t_item.gender"]);
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->select();
			// $map["t_item.gender"] = array('IN',$genders );
		}else{
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->select();
		}
		if(in_array("colors", $filterArray)){
			// unset($map["t_item.color"]);
			$color = D('Item')->field('distinct t_item.color')->where($map)->select();
			// $map["t_item.color"] = array('IN',$colors );
		}else{
			$color = D('Item')->field('distinct t_item.color')->where($map)->select();
		}
		
		//entry
		$this->assign('by',$by);
		$this->assign('byValue',$byValue);
		//selected filters
		$this->assign('brandsChecked', json_encode($brandsChecked));
		$this->assign('categoriesChecked',json_encode($categoriesChecked));
		$this->assign('gendersChecked',json_encode($gendersChecked));
		$this->assign('colorsChecked',json_encode($colorsChecked));
		//pageing
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		//refresh filters
		$this->assign('category', $category);
		$this->assign('brand', $brand);
		$this->assign('gender', $gender);
		$this->assign('color', $color);
		//show item list page
		$this->display('index');
	}
}
