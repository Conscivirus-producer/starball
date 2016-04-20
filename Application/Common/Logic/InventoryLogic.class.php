<?php
	namespace Common\Logic;
	use Common\Model\InventoryModel;
	class InventoryLogic extends InventoryModel{
		public function getInventoryByItemId($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
		
		public function getSizeDescriptionById($id){
			$map['inventoryId'] = $id;
			$data = $this->where($map)->select();
			return getSizeDescriptionByAge($data[0]['age']);
		}

		public function insertInventoriesforOneItem($itemId, $inventoryArray){
			for ($i = 0; $i < count($inventoryArray); $i++) {
				$data["age"] = $inventoryArray[$i]["age"];
				$data["inventory"] = $inventoryArray[$i]["inventory"];
				$data["itemId"] = $itemId;
				if ($this->add($data) === false) {
					return false;
				}
			}
			return true;
		}

		public function deleteInventoriesByItemId($itemId){
			$map["itemId"] = $itemId;
			if ($this->where($map)->count() == 0) {
				return true;
			}
			return ($this->where($map)->delete() !== false);
		}

		public function updateInventoriresForOneItem($itemId, $inventoryArray) {
			if ($this->deleteInventoriesByItemId($itemId) === false) {
				return false;
			} else {
				return $this->insertInventoriesforOneItem($itemId, $inventoryArray);
			}
		}
	}


?>