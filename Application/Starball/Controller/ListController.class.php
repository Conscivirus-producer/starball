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
		$tag = I('get.tags');
		
		//check inventory availability
		$map["isAvailable"] = array('NEQ', "0");
		//check page entry
		if($by == "time"){
			if($byValue == "commingsoon"){
				$map["isAvailable"] = array('EQ', "2");
			}else{
				$map["DateDiff(now(),lastUpdatedDate)"] = array('ELT', $byValue); 
			}
		}elseif($by == "brand"){
			$map["t_item.brandId"] = array('EQ', $byValue);
		}elseif($by == "baby"){
			$map["grade"] = array('EQ',"1");
		}elseif($by == "boy"){
			$map["gender"] = array('EQ', 'M');
		}elseif($by == "girl"){
			$map["gender"] = array('EQ', 'F');
		}elseif($by == "search"){
			$search["_logic"] = "or";
			$search["t_item.name"] = array('like', "%".$byValue."%");
			$search["t_item.detailDescription"] = array('like', "%".$byValue."%");
			$map["_complex"] = $search;
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
			// $map["t_item.grade"] = array('IN', $ages);
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
		if($tag == ""){
			$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->order('brandId desc,categoryId desc, t_item.itemId desc')
							 ->page($p.',12')
							 ->select();
		}else{
			$itemList = D('Item')->field('distinct t_item.itemId, t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.currency = '".$this->getCurrency()."'")
							 ->join("t_tag tg ON tg.itemId = t_item.itemId AND tg.tagName ='".$tag."'")
							 ->order('brandId desc,categoryId desc, t_item.itemId desc')
							 ->page($p.',12')
							 ->select();
		}

		//age list for each item
		for ($i=0; $i < count($itemList); $i++) {
			$ageMap["t_inventory.itemId"] = array('EQ', $itemList[$i]["itemId"]); 
			$ageList[$itemList[$i]["itemId"]] = D('Inventory')->field('distinct t_inventory.age')->where($ageMap)->select();
		}
		logInfo(json_encode($ageList));
		$count = D('Item')->where($map)->count();
		$Page = new \Think\Page($count,12);
		$show = $Page->show();					 
		
		$filterArray = array_keys(I('get.'));
		if(in_array("categories", $filterArray)){
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId')->group('t_item.categoryId')->order('categoryId desc')->select();
		}else{
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId')->group('t_item.categoryId')->order('categoryId desc')->select();
		}
		if(in_array("brands", $filterArray)){
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		}else{
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		}
		if(in_array("genders", $filterArray)){
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}else{
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}
		if(in_array("colors", $filterArray)){
			$color = D('Item')->field('distinct t_item.color')->where($map)->select();
		}else{
			$color = D('Item')->field('distinct t_item.color')->where($map)->select();
		}
		if(in_array("ages", $filterArray)){
			$age = D('Item')->field('distinct inv.age')
							->where($map)
							->join('t_inventory inv ON inv.itemId = t_item.itemId')
							->select();
			for ($i=0; $i < count($age); $i++) {
				$age[$i]["ageName"] = getSizeDescriptionByAge($age[$i]["age"]);
			}
		}else{
			$age = D('Item')->field('distinct inv.age')
							->where($map)
							->join('t_inventory inv ON inv.itemId = t_item.itemId')
							->select();
			for ($i=0; $i < count($age); $i++) {
				$age[$i]["ageName"] = getSizeDescriptionByAge($age[$i]["age"]);
			}
		}
		if(in_array("seasons", $filterArray)){
			$season = D('Item')->field('distinct t_item.season')->where($map)->select();
		}else{
			$season = D('Item')->field('distinct t_item.season')->where($map)->select();
		}
		
		//entry
		$this->assign('by',$by);
		$this->assign('byValue',$byValue);
		//selected filters
		$this->assign('brandsChecked', json_encode($brandsChecked));
		$this->assign('categoriesChecked',json_encode($categoriesChecked));
		$this->assign('gendersChecked',json_encode($gendersChecked));
		$this->assign('colorsChecked',json_encode($colorsChecked));
		$this->assign('agesChecked', json_encode($agesChecked));
		$this->assign('seasonsChecked', json_encode($seasonsChecked));
		//pageing
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		//refresh filters
		$this->assign('category', $category);
		$this->assign('brand', $brand);
		$this->assign('gender', $gender);
		$this->assign('color', $color);
		$this->assign('age', $age);
		$this->assign('season', $season);
		//show item list page
		$this->display('index');
	}
}
