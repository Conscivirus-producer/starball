<?php
	namespace Common\Logic;
	use Common\Model\ItemPriceModel;
	class ItemPriceLogic extends ItemPriceModel{
		
		public function getPriceMap($itemId, $currency){
			$map['itemId'] = $itemId;
			$map['currency'] = $currency;
			$result = $this->where($map)->select();
			
			$itemData = D('Item', 'Logic')->findById($itemId);
			$discount = $itemData['discount'];
			$data = array();
			foreach($result as $record){
				if($discount != 100){
					$data[$record['inventoryId']] = round($record['price'] * $discount/100, 1);
				}else{
					$data[$record['inventoryId']] = $record['price'];
				}
			}
			return $data;
		}
		
		public function getDefaultPrice($itemId, $currency){
			$map['itemId'] = $itemId;
			$map['currency'] = $currency;
			$result = $this->where($map)->order('price asc')->select();
			$defaultPrice = $result[0]['price'];
			$itemData = D('Item', 'Logic')->findById($itemId);
			$discount = $itemData['discount'];
			if($discount != 100){
				return round($defaultPrice * $discount/100, 1);
			}
			return $defaultPrice;
		}

		// related with inventory ID
		public function insertItemPrices($itemId, $inventoryId, $priceArray) {
			$data = array();
			$data["itemId"] = $itemId;
			$data["inventoryId"] = $inventoryId;
			$data["updatedDate"] = date('y-m-d h:i:s',time());
			foreach ($priceArray as $key => $value) {
				$data["price"] = $value;
				$data["currency"] = $key;
				$data["autoAssign"] = 0;
				if ($this->add($data) === false) {
					return false;
				}
			}
			return true;
		}

		public function getClassifiedPriceByItemId($itemId) {
			$result = array();
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			$count = count($data);
			for ($i = 0; $i < $count; $i++) {
				$result[$data[$i]["currency"]] = $data[$i];
			}
			return $result;
		}

		public function updateItemPrices($itemId, $priceArray, $lastUpdatedDate, $inventoryId) {
			foreach ($priceArray as $key => $value) {
				$map["itemId"] = $itemId;
				$map["currency"] = $key;
				$map["inventoryId"] = $inventoryId;
				$data["price"] = $value;
				$data["updatedDate"] = $lastUpdatedDate;
				if ($this->where($map)->data($data)->save() === false) {
					return false;
				}
			}
			return true;
		}

		public function deleteItemPricesByItemId($itemId) {
			$map["itemId"] = $itemId;
			if ($this->where($map)->count() == 0) {
				return true;
			}
			if ($this->where($map)->delete() !== false) {
				return true;
			} else {
				return false;
			}
		}

		public function getClassifiedItemPricesByInventoryId($inventoryId) {
			$map["inventoryId"] = $inventoryId;
			$data = $this->where($map)->select();
			for ($i = 0; $i < count($data); $i++) {
				$result[$data[$i]["currency"]] = $data[$i];
			}
			return $result;
		}
	}

?>