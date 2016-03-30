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

	}


?>