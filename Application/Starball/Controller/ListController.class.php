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
		$colors = urldecode($colors);
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
			$map["gender"] = array('IN', 'M,A');
		}elseif($by == "girl"){
			$map["gender"] = array('IN', 'F,A');
		}elseif($by == "search"){
			$byValue = urldecode($byValue);
			$search["_logic"] = "or";
			$search["t_item.name"] = array('like', "%".$byValue."%");
			$search["t_item.detailDescription"] = array('like', "%".$byValue."%");
			$map["_complex"] = $search;
		}elseif($by == "discount"){
			$map["t_item.discount"] = array("NEQ", "100");
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
			$ageFilter = str_replace('a', ',', $ages);
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
			if($ages == ""){
				$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
				$count = D('Item')->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
				 if($count == 0 && $by == "brand"){
				 	$this->redirect('Starball/List/shoes/by/shoes/byValue/shoes/brands/'.$byValue);
				 }
			}else{
				$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_inventory inv ON inv.itemId = t_item.itemId and inv.age like '%".$ageFilter."%'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
				$count = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_inventory inv ON inv.itemId = t_item.itemId and inv.age like '%".$ageFilter."%'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
			}
		}else{
			$itemList = D('Item')->field('distinct t_item.itemId, t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.currency = '".$this->getCurrency()."'")
							 ->join("t_tag tg ON tg.itemId = t_item.itemId AND tg.tagName ='".$tag."'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
			$count = D('Item')->field('distinct t_item.itemId, t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.currency = '".$this->getCurrency()."'")
							 ->join("t_tag tg ON tg.itemId = t_item.itemId AND tg.tagName ='".$tag."'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
		}

		//age list for each item
		for ($i=0; $i < count($itemList); $i++) {
			$ageMap["t_inventory.itemId"] = array('EQ', $itemList[$i]["itemId"]); 
			$itemList[$i]["ageList"] = D('Inventory')->field('distinct t_inventory.age')->where($ageMap)->select();
			$itemList[$i]["ageList"] = expodeAndDistinctAgeArray($itemList[$i]["ageList"]);
		}
		
		
		$Page = new \Think\Page($count,18);
		$show = $Page->show();					 
		
		$filterArray = array_keys(I('get.'));
		if(in_array("categories", $filterArray)){
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->group('t_item.categoryId')->order('t_item.categoryId desc')->select();
		}else{
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->group('t_item.categoryId')->order('t_item.categoryId desc')->select();
		}
		if(in_array("brands", $filterArray)){
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->group('t_item.brandId')->order('brandId asc')->select();
		}else{
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_brand brd ON brd.brandId = t_item.brandId')->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->group('t_item.brandId')->order('brandId asc')->select();
		}
		if(in_array("genders", $filterArray)){
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.categoryId !='2'")->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}elseif($gender[$i]["gender"] == "A"){
					$gender[$i]["genderName"] = "不限";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}else{
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}elseif($gender[$i]["gender"] == "A"){
					$gender[$i]["genderName"] = "不限";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}
		if(in_array("colors", $filterArray)){
			$color = D('Item')->field('distinct t_item.color')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->select();
		}else{
			$color = D('Item')->field('distinct t_item.color')->where($map)->join("t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type !='2'")->select();
		}
		$itemIds = array();
		
		foreach($itemList as $item){
			array_push($itemIds, $item['itemId']);
		}
		$ageFilterMap['itemId'] = array('in', $itemIds);
		if(in_array("ages", $filterArray) && !empty($itemIds)){
			$age = D('Inventory')->field('distinct age')->where($ageFilterMap)->select();
			$age = expodeAndDistinctAgeArray($age);
		}else if(!empty($itemIds)){
			$age = D('Inventory')->field('distinct age')->where($ageFilterMap)->select();
			$age = expodeAndDistinctAgeArray($age);
		}
		if(in_array("seasons", $filterArray)){
			$season = D('Item')->field('distinct t_item.season')->where($map)->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")->select();
		}else{
			$season = D('Item')->field('distinct t_item.season')->where($map)->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type !='2'")->select();
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
		//age list
		//show item list page
		$this->display('index');
	}
	
	public function accessory(){
		$this->commonProcess();
		
		//check inventory availability
		$map["isAvailable"] = array('NEQ', "0");
		
		
		//get item list and paging
		$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
					 ->where($map)
					 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
					 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
					 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='3'")
					 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
					 ->page($p.',18')
					 ->select();
		$count = D('Item')->field('t_item.*, img.image, price.price')
					 ->where($map)
					 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
					 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
					 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='3'")
					 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
					 ->count('distinct t_item.itemId');

		//age list for each item
		for ($i=0; $i < count($itemList); $i++) {
			$ageMap["t_inventory.itemId"] = array('EQ', $itemList[$i]["itemId"]); 
			$itemList[$i]["ageList"] = D('Inventory')->field('distinct t_inventory.age')->where($ageMap)->select();
			for ($j=0; $j < count($itemList[$i]["ageList"]); $j++) { 
				$itemList[$i]["ageList"][$j]["age"] = getSizeDescriptionByAge($itemList[$i]["ageList"][$j]["age"]);
			}
		}
		
		$Page = new \Think\Page($count,18);
		$show = $Page->show();					 
		
		//pageing
		$this->assign('page', $show);
		$this->assign('itemList',$itemList);
		//show item list page
		$this->display('accessory');
	}
	
	public function hotItem(){
		$this->commonProcess();
		//select type as 'S'
		$map["type"] = array("EQ", "S");
		//get hotitem list and paging
		$itemList = D('t_hotitem')
					 ->where($map)
					 ->order('t_hotitem.sequence desc')
					 ->limit(9)
					 ->select();

		$this->assign('itemList',$itemList);
		//show item list page
		$this->display('boutique');
	}
	
	public function shoes($p=1){
		$this->commonProcess();
		
		//get params
		$brands = I('get.brands');
		$categories = I('get.categories');
		$genders = I('get.genders');
		$ages = I('get.ages');
		$colors = I('get.colors');
		$seasons = I('get.seasons');
		
		//check inventory availability
		$map["isAvailable"] = array('NEQ', "0");
		
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
			$ageFilter = str_replace('a', ',', $ages);
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
			if($ages == ""){
				$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
				$count = D('Item')->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
			}else{
				$itemList = D('Item')->distinct(true)->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_inventory inv ON inv.itemId = t_item.itemId and inv.footSize like '%".$ageFilter."%'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
				$count = D('Item')->field('t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.price = (select min(price) from t_itemprice where currency = '".$this->getCurrency()."' and itemId = t_item.itemId)")
							 ->join("t_inventory inv ON inv.itemId = t_item.itemId and inv.footSize like '%".$ageFilter."%'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
			}
		}else{
			$itemList = D('Item')->field('distinct t_item.itemId, t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.currency = '".$this->getCurrency()."'")
							 ->join("t_tag tg ON tg.itemId = t_item.itemId AND tg.tagName ='".$tag."'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->page($p.',18')
							 ->select();
			$count = D('Item')->field('distinct t_item.itemId, t_item.*, img.image, price.price')
							 ->where($map)
							 ->join('t_image img ON img.itemId = t_item.itemId AND img.sequence = (SELECT MIN(sequence) FROM t_image WHERE itemId = img.itemId )')
							 ->join("t_itemprice price ON price.itemId = t_item.itemId and price.currency = '".$this->getCurrency()."'")
							 ->join("t_tag tg ON tg.itemId = t_item.itemId AND tg.tagName ='".$tag."'")
							 ->join("t_category cat ON cat.categoryId = t_item.categoryId and cat.type ='2'")
							 ->order('brandId desc,t_item.categoryId desc, t_item.itemId desc')
							 ->count('distinct t_item.itemId');
		}

		//age list for each item
		for ($i=0; $i < count($itemList); $i++) {
			$itemList[$i]["ageList"] = D('Inventory', 'Logic')->getFootSizeListForShoes('', array($itemList[$i]["itemId"]));
		}
		
		$Page = new \Think\Page($count,18);
		$show = $Page->show();					 
		
		$filterArray = array_keys(I('get.'));
		if(in_array("categories", $filterArray)){
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->group('t_item.categoryId')->order('categoryId desc')->select();
		}else{
			$category = D('Item')->field('distinct t_item.categoryId, ctg.categoryName, count(*) as count')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId  and ctg.type = 2')->group('t_item.categoryId')->order('categoryId desc')->select();
		}
		if(in_array("brands", $filterArray)){
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		}else{
			$brand = D('Item')->field('distinct t_item.brandId, brd.brandName, count(*)')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->join('t_brand brd ON brd.brandId = t_item.brandId')->group('t_item.brandId')->order('brandId asc')->select();
		}
		if(in_array("genders", $filterArray)){
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}elseif($gender[$i]["gender"] == "A"){
					$gender[$i]["genderName"] = "不限";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}else{
			$gender = D('Item')->field('distinct t_item.gender')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
			for ($i=0; $i < count($gender); $i++) { 
				if($gender[$i]["gender"] == "M"){
					$gender[$i]["genderName"] = "男";
				}elseif($gender[$i]["gender"] == "A"){
					$gender[$i]["genderName"] = "不限";
				}else{
					$gender[$i]["genderName"] = "女";
				}
			}
		}
		if(in_array("colors", $filterArray)){
			$color = D('Item')->field('distinct t_item.color')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
		}else{
			$color = D('Item')->field('distinct t_item.color')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
		}
		$itemIds = array();
		foreach($itemList as $item){
			array_push($itemIds, $item['itemId']);
		}
		if(in_array("ages", $filterArray)){
			$age = D('Inventory', 'Logic')->getFootSizeListForShoes('', $itemIds);
		}else{
			$age = D('Inventory', 'Logic')->getFootSizeListForShoes('', $itemIds);
		}
		if(in_array("seasons", $filterArray)){
			$season = D('Item')->field('distinct t_item.season')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
		}else{
			$season = D('Item')->field('distinct t_item.season')->where($map)->join('t_category ctg ON ctg.categoryId = t_item.categoryId and ctg.type = 2')->select();
		}
		
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
		$this->display('shoes');
	}
}
