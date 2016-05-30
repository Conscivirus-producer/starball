<?php
	namespace Common\Logic;
	use Common\Model\ItemModel;
	class ItemLogic extends ItemModel{
		public function getItemWithBrandAndCategoryById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->field('t_brand.brandName, t_category.categoryName, t_item.*')->where($map)->
				join('t_brand on t_item.brandId = t_brand.brandId')->
				join('t_category on t_item.categoryId = t_category.categoryId')->find();
			return $data;
		}
		
		public function findById($itemId){
			$map['itemId'] = $itemId;
			return $this->where($map)->find();
		}

		public function insertOneItem($data) {
			// add the inventory
			$inventoryLogic = D("Inventory", "Logic");
			$inventoryArray = $data["inventory"];
			// add the tag
			$tagLogic = D("Tag", "Logic");
			$tagString = $data["tag"];
			//$priceArray = array();
			//$priceArray["CNY"] = $data["priceCNY"];
			//$priceArray["HKD"] = $data["priceHKD"];
			$imageLogic = D("Image", "Logic");
			$imageArray = split(",",$data["images_array"]);
			$newData = $data;
			unset($newData["images_array"]);
			//unset($newData["priceCNY"]);
			//unset($newData["priceHKD"]);
			unset($newData["tag"]);
			unset($newData["inventory"]);
			$newData["lastUpdatedDate"] = date('y-m-d h:i:s',time());
			$index = $this->add($newData);
			if($index === false) {
				return false;
			} else {
				$res = ($imageLogic->insertMultipleImages($index, $imageArray)) & ($tagLogic->insertTagsForOneItem($index, $tagString)) & ($inventoryLogic->insertInventoriesforOneItem($index, $inventoryArray));
				return $res;
			}
		}

		public function getBrandNameListByGrade($grade, $gender){
			$map['grade'] = array('in', $grade);
			if($gender != ''){
				$map['gender'] = array('in', $gender);
			}
			$data = $this->distinct(true)->field('t_brand.brandName, t_brand.brandId')->where($map)->join('t_brand on t_item.brandId = t_brand.brandId')->select();
			return $data;
		}
		
		public function getCategoryNameByGrade($grade, $gender){
			$map['grade'] = array('in', $grade);
			if($gender != ''){
				$map['gender'] = array('in', $gender);
			}
			$data = $this->distinct(true)->field('t_item.categoryId, t_category.categoryName')->where($map)->join('t_category on t_item.categoryId = t_category.categoryId')->select();
			return $data;
		}
		
		public function getAgeListByGrade($grade, $gender){
			$itemSize = C('ITEMSIZE');
			$ageDescriptionArray = array();
			if(in_array('1', $grade)){
			//婴儿
				foreach($itemSize as $key=>$value){
					if(($key - '7') > 0){
						break;
					}
					array_push($ageDescriptionArray, $value[0]);
				}
			}else if(in_array('2', $grade)){
				//男孩,女孩
				foreach($itemSize as $key=>$value){
					if(($key - '7') > 0){
						array_push($ageDescriptionArray, $value[0]);
					}
				}
			}
			return $ageDescriptionArray;
		}
		
		public function getAgeListByGradeCopy($grade, $gender){
			$map['grade'] = array('in', $grade);
			if($gender != ''){
				$map['gender'] = array('in', $gender);
			}
			$data = $this->distinct(true)->field('t_inventory.age')->where($map)->join('t_inventory on t_inventory.itemId = t_item.itemId')->select();
			$ageArray = array();
			foreach($data as $ageSection){
				//age是有区间的,拆分age,然后把值distinct出来
				$ageSectionArray = explode(',', $ageSection['age']);
				foreach($ageSectionArray as $age){
					if($age != '' && !in_array($age, $ageArray)){
						array_push($ageArray, $age);
					}
				}
			}
			asort($ageArray);
			
			//把数字转化为描述
			$itemSize = C('ITEMSIZE');
			$ageDescriptionArray = array();
			foreach($ageArray as $age){
				array_push($ageDescriptionArray, $itemSize[$age][0]);
			}
			return $ageDescriptionArray;
		}

		// 拿取所有信息:基本信息,价格,图片
		// 价格必须根据库存来拿
		public function getItemInformationById($itemId) {
			//$itemPriceLogic = D("ItemPrice", "Logic");
			$imageLogic = D("Image", "Logic");
			$tagLogic = D("Tag", "Logic");
			$inventoryLogic = D("Inventory", "Logic");
			$itemMap["itemId"] = $itemId;
			$basicInformation = $this->where($itemMap)->select();
			$result = current($basicInformation);
			$result["images"] = $imageLogic->getImageById($itemId);
			$result["tag"] = $tagLogic->getTagStringByItemId($itemId);
			$result["inventory"] = $inventoryLogic->getInventoryByItemId($itemId);
			return $result;
		}

		public function updateOneItem($data) {
			$itemId = ''.$data["itemId"];
			$inventoryLogic = D("Inventory", "Logic");
			$inventoryArray = $data["inventory"];
			$tagLogic = D("Tag", "Logic");
			$tagString = $data["tag"];
			$imageLogic = D("Image", "Logic");
			$imageArray = split(",",$data["images_array"]);
			$newData = $data;
			unset($newData["images_array"]);
			unset($newData["tag"]);
			unset($newData["inventory"]);
			$lastUpdatedDate = date('y-m-d h:i:s',time());
			$newData["lastUpdatedDate"] = $lastUpdatedDate;
			if ($this->save($newData) == false) {
				return false;
			} else {
				$res = ($imageLogic->updateOneItemImages($itemId, $imageArray)) & ($tagLogic->updateTagsForOneItem($itemId, $tagString)) & ($inventoryLogic->updateInventoriresForOneItem($itemId, $inventoryArray));
				return $res;
			}
		}

		public function bulkUploadUsingCSV($contents) {
			$res = array(
				"status" => "0",
				"row" => ""
			);
			$brandLogic = D("Brand", "Logic");
			$itemPriceLogic = D("ItemPrice", "Logic");
			$categoryLogic = D("Category", "Logic");
			$tagLogic = D("Tag", "Logic");
			$gradeMap = array("Baby" => "1", "Child" => "2", "其它" => "3");
			$genderMap = array(
				"男" => "M",
				"女" => "F"
			);
			$rows = split("\n", $contents);
			$product = array();
			$priceArray = array();
			// used for addAll, make sure that all the data are right
			$products = array();
			$priceArrays = array();
			$tagStringArrays = array();
			$currentRow = 1;
			// validation
			for ($i = 0; $i < count($rows); $i++) {
				$columns = split(",", $rows[$i]);
				// len of fields must match
				if (count($columns) != 13) {
					$res["row"] = $currentRow;
					return $res;
				}
				$product["name"] = trim($columns[0]);
				if ($product["name"] == "商品名称") {
					$currentRow++;
					continue;
				}
				$product["color"] = trim($columns[1]);
				$product["detailDescription"]= trim($columns[2]);
				$product["component"] = trim($columns[3]);
				$product["brandId"] = $brandLogic->getBrandIdByBrandName(trim($columns[4]));
				$product["categoryId"] = $categoryLogic->getCategoryIdByCategoryName(trim($columns[5]));
				$product["grade"] = $gradeMap[trim($columns[6])];
				$product["gender"] = $genderMap[trim($columns[7])];

				$priceArray["HKD"] = trim($columns[8]);
				$priceArray["CNY"] = trim($columns[9]);
				$product["season"] = trim($columns[10]);
				$tagString = trim($columns[11]);
				$product["discount"] = trim($columns[12]);
				$product["lastUpdatedDate"] = date('y-m-d h:i:s',time());
				$product["isAvailable"] = "0";
				array_push($products, $product);
				array_push($priceArrays, $priceArray);
				array_push($tagStringArrays, $tagString);
				$currentRow++;
			}
			$index = $this->addAll($products);
			if ($index == false) {
				return $res;
			} else {
				for ($i = 0; $i < count($priceArrays); $i++) {
					if ($itemPriceLogic->insertItemPrices($index, $priceArrays[$i]) === false) {
						$res["row"] = $index;
						return $res;
					}
					if ($tagLogic->insertTagsForOneItem($index, $tagStringArrays[$i]) === false) {
						$res["row"] = $index;
						return $res;
					}
					$index++;
				}
				$res["status"] = "1";
				return $res;
			}
		}

		public function search($conditions) {
			$itemPriceLogic = D("ItemPrice", "Logic");
			$map["name"] = array("like", "%".$conditions["name"]."%");
			$map["color"] = array("like", "%".$conditions["color"]."%");
			$map["detailDescription"] = array("like", "%".$conditions["detailDescription"]."%");
			$map["component"] = array("like", "%".$conditions["component"]."%");
			if ($conditions["brandId"] != "nothing") {
				$map["brandId"] = array('eq',$conditions["brandId"]);
			}
			if ($conditions["categoryId"] != "nothing") {
				$map["categoryId"] = array('eq',$conditions["categoryId"]);
			}
			if ($conditions["grade"] != "nothing") {
				$map["grade"] = array('eq',$conditions["grade"]);
			}
			if ($conditions["gender"] != "nothing") {
				$map["gender"] = array('eq',$conditions["gender"]);
			}
			$map["season"] = array("like", "%".$conditions["season"]."%");
			$data = $this->where($map)->order(array('lastUpdatedDate'=>'desc'))->select();
			$res = array();
			// filter price
			for($i = 0; $i < count($data); $i++) {
				$temp = $data[$i];
				$itemId = $temp["itemId"];
				$prices = $itemPriceLogic->getClassifiedPriceByItemId($itemId);
				$priceHKD = (float)$prices["HKD"]["price"];
				$priceCNY = (float)$prices["CNY"]["price"];
				if ($priceHKD >= $this->normalizePrice($conditions["priceHKDL"], "l") && $priceHKD <= $this->normalizePrice($conditions["priceHKDH"], "h") && $priceCNY >= $this->normalizePrice($conditions["priceCNYL"], "l") && $priceCNY <= $this->normalizePrice($conditions["priceCNYH"], "h")) {
					$temp["priceHKD"] = $priceHKD;
					$temp["priceCNY"] = $priceCNY;
					array_push($res, $temp);
				}
			}
			return $res;
		}

		public function normalizePrice($price, $type) {
			if ($price == "") {
				if ($type == "l") {
					return (float)0;
				} else {
					return (float)100000000;
				}
			} else {
				return (float)$price;
			}
		}

		public function deleteOneItemByItemId($itemId) {
			$itemPriceLogic = D("ItemPrice", "Logic");
			$imageLogic = D("Image", "Logic");
			$map["itemId"] = $itemId;
			if ($this->where($map)->delete() === false) {
				return false;
			} else {
				return ($itemPriceLogic->deleteItemPricesByItemId($itemId) & $imageLogic->deleteImagesByItemId($itemId));
			}
		}

	}


?>