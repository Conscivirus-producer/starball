<?php
	namespace Common\Logic;
	use Common\Model\ItemModel;
	class ItemLogic extends ItemModel{
		public function getItemById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}

		public function insertOneItem($data) {
			$imageLogic = D("Image", "Logic");
			$newData = $data;
			$imageArray = split(",",$data["images_array"] );
			unset($newData["images_array"]);
			$newData["lastUpdatedDate"] = date('y-m-d h:i:s',time());
			$newData["isAvailable"] = "1";
			$newData["link"] = "";
			$newData["discount"] = 100;
			$newData["appendWords"] = "";
			$index = $this->add($newData);
			if($index == false) {
				return false;
			} else {
				$res = $imageLogic->insertMultipleImages($index, $imageArray);
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
			$data = $this->distinct(true)->field('t_item.categoryId, t_category.categoryName')->where($map)->join('t_category on t_item.categoryId = t_brand.categoryId')->select();
			return $data;
		}
	}


?>