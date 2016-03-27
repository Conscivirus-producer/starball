<?php
	namespace Common\Logic;
	use Common\Model\ItemModel;
	class ItemLogic extends ItemModel{
		public function getItemById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
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
	}


?>