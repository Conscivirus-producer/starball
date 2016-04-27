<?php
	namespace Common\Logic;
	use Common\Model\InventoryModel;
	class InventoryLogic extends InventoryModel{
		public function getInventoryByItemId($itemId){
			$itemPriceLogic = D("ItemPrice", "Logic");
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			for ($i = 0; $i < count($data); $i++) {
				$inventoryId = $data[$i]["inventoryId"];
				$priceArray = $itemPriceLogic->getClassifiedItemPricesByInventoryId($inventoryId);
				$data[$i]["priceCNY"] = $priceArray["CNY"];
				$data[$i]["priceHKD"] = $priceArray["HKD"];
			}
			return $data;
		}
		
		public function getInventoryAndPriceByItemId($itemId, $currency){
			$map['t_inventory.itemId'] = $itemId;
			$map['price.currency'] = $currency;
			$data = $this->field('t_inventory.*, price.price')->where($map)->join('t_itemprice price ON price.inventoryId = t_inventory.inventoryId')->select();
			return $data;
		}
		
		public function getSizeDescriptionById($id){
			$map['inventoryId'] = $id;
			$data = $this->where($map)->select();
			return getSizeDescriptionByAge($data[0]['age']);
		}
		
		public function updateInventory($inventoryId, $changedQuantity){
			/*$map['inventoryId'] = $inventoryId;
			$result = $this->where($map)->find();
			
			$data['inventoryId'] = $inventoryId;
			$data['inventory'] = $result['inventory'] + $changedQuantity;
			$this->where($map)->save($data);*/
			
			$this->execute("update t_inventory set inventory = inventory + ".$changedQuantity." where inventoryId = ".$inventoryId);
		}

		public function insertInventoriesforOneItem($itemId, $inventoryArray){
			// insert item prices while inserting inventories
			$itemPriceLogic = D("ItemPrice", "Logic");
			$priceArray = array();
			for ($i = 0; $i < count($inventoryArray); $i++) {
				$data["age"] = $inventoryArray[$i]["age"];
				$data["inventory"] = $inventoryArray[$i]["inventory"];
				$data["itemId"] = $itemId;
				$priceArray["CNY"] = $inventoryArray[$i]["priceCNY"];
				$priceArray["HKD"] = $inventoryArray[$i]["priceHKD"];
				$inventoryId = $this->add($data);
				if ($inventoryId === false) {
					return false;
				} else {
					if ($itemPriceLogic->insertItemPrices($itemId, $inventoryId, $priceArray) === false) {
						return false;
					}
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
			$itemPriceLogic = D("ItemPrice", "Logic");
			if ($this->deleteInventoriesByItemId($itemId) === false) {
				return false;
			} else {
				if ($itemPriceLogic->deleteItemPricesByItemId($itemId) === false) {
					return false;
				}
				return $this->insertInventoriesforOneItem($itemId, $inventoryArray);
			}
		}
	}


?>