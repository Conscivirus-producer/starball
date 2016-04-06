<?php
	namespace Common\Logic;
	use Common\Model\ImageModel;
	class ImageLogic extends ImageModel{
		public function getImageById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->order('sequence')->select();
			return $data;
		}
		public function insertMultipleImages($itemId, $imageArray) {
			$count = count($imageArray);
			for ($i = 0; $i < $count; $i++) {
				$data["itemId"] = $itemId;
				$data["image"] = $imageArray[$i];
				$data["sequence"] = $i;
				$index = $this->add($data);
				if ($index == false) {
					return false;
				}
			}
			return true;
		}
		public function updateOneItemImages($itemId, $imageArray) {
			$map['itemId'] = $itemId;
			if ($this->where($map)->delete() == false) {
				return false;
			} else {
				return $this->insertMultipleImages($itemId, $imageArray);
			}
		}
	}


?>