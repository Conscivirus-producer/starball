<?php
	namespace Common\Logic;
	use Common\Model\ItemPriceModel;
	class ItemPriceLogic extends ItemPriceModel{
		public function getPriceByItemId($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
		
		public function getPriceMap($itemId){
			$result = $this->getPriceByItemId($itemId);
			$data = array();
			foreach($result as $record){
				$data[$record['currency']] = $record['price'];
			}
			return $data;
		}

		public function insertItemPrices($itemId, $priceArray) {
			$data = array();
			$data["itemId"] = $itemId;
			foreach ($priceArray as $key => $value) {
				$data["price"] = $value;
				$data["currency"] = $key;
				$data["autoAssign"] = 0;
				if ($this->add($data) == false) {
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

		public function updateItemPrices($itemId, $priceArray, $lastUpdatedDate) {
			foreach ($priceArray as $key => $value) {
				$map["itemId"] = $itemId;
				$map["currency"] = $key;
				$data["price"] = $value;
				$data["updatedDate"] = $lastUpdatedDate;
				if ($this->where($map)->data($data)->save() == false) {
					return false;
				}
			}
			return true;
		}
	}

?>