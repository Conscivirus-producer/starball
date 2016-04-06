<?php
	namespace Common\Logic;
	use Common\Model\ItemModel;
	class ItemLogic extends ItemModel{
		public function getItemById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->field('t_brand.brandName, t_item.*')->where($map)->join('t_brand on t_item.brandId = t_brand.brandId')->select();
			return $data;
		}

		public function insertOneItem($data) {
			$itemPriceLogic = D("ItemPrice", "Logic");
			$priceArray = array();
			$priceArray["CNY"] = $data["priceCNY"];
			$priceArray["HKD"] = $data["priceHKD"];
			$imageLogic = D("Image", "Logic");
			$imageArray = split(",",$data["images_array"]);
			$newData = $data;
			unset($newData["images_array"]);
			unset($newData["priceCNY"]);
			unset($newData["priceHKD"]);
			$newData["lastUpdatedDate"] = date('y-m-d h:i:s',time());
			$newData["isAvailable"] = "1";
			$newData["discount"] = 100;
			$index = $this->add($newData);
			if($index == false) {
				return false;
			} else {
				$res = ($imageLogic->insertMultipleImages($index, $imageArray)) & ($itemPriceLogic->insertItemPrices($index, $priceArray));
				return $res;
			}
		}

		public function getBrandNameListByGrade($grade, $gender){
			$map['grade'] = $grade;
			if($gender != ''){
				$map['gender'] = $gender;
			}
			$data = $this->distinct(true)->field('t_brand.brandName, t_brand.brandId')->where($map)->join('t_brand on t_item.brandId = t_brand.brandId')->select();
			return $data;
		}
		
		public function getCategoryNameByGrade($grade, $gender){
			$map['grade'] = $grade;
			if($gender != ''){
				$map['gender'] = $gender;
			}
			$data = $this->distinct(true)->field('t_item.categoryId, t_category.categoryName')->where($map)->join('t_category on t_item.categoryId = t_category.categoryId')->select();
			return $data;
		}

		//拿取所有信息:基本信息,价格,图片
		public function getItemInformationById($itemId) {
			$itemPriceLogic = D("ItemPrice", "Logic");
			$imageLogic = D("Image", "Logic");
			$itemMap["itemId"] = $itemId;
			$basicInformation = $this->where($itemMap)->select();
			$result = current($basicInformation);
			$result["itemPrice"] = $itemPriceLogic->getClassifiedPriceByItemId($itemId);
			$result["images"] = $imageLogic->getImageById($itemId);
			return $result;
		}

		public function updateOneItem($data) {
			$itemId = ''.$data["itemId"];
			$itemPriceLogic = D("ItemPrice", "Logic");
			$priceArray = array();
			$priceArray["CNY"] = $data["priceCNY"];
			$priceArray["HKD"] = $data["priceHKD"];
			$imageLogic = D("Image", "Logic");
			$imageArray = split(",",$data["images_array"]);
			$newData = $data;
			unset($newData["images_array"]);
			unset($newData["priceCNY"]);
			unset($newData["priceHKD"]);
			$lastUpdatedDate = date('y-m-d h:i:s',time());
			$newData["lastUpdatedDate"] = $lastUpdatedDate;
			$newData["isAvailable"] = "1";
			$newData["discount"] = 100;
			if ($this->save($newData) == false) {
				return false;
			} else {
				$res = ($itemPriceLogic->updateItemPrices($itemId, $priceArray, $lastUpdatedDate)) & ($imageLogic->updateOneItemImages($itemId, $imageArray));
				return $res;
			}
		}
	}


?>