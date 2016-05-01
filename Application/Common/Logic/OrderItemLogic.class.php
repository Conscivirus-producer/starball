<?php
	namespace Common\Logic;
	use Common\Model\OrderItemModel;
	class OrderItemLogic extends OrderItemModel{
		public function create($data){
			if($data['updatedDate'] == ''){
				$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			}
			$this->add($data);	
		}
		
		public function getOrderItemById($id){
			 $map['id'] = $id;
			 return $this->where($map)->find();
		}
		
		public function getOrderItemsByOrdeId($orderId){
			$map['orderId'] = $orderId;
			return $this->where($map)->order('updatedDate desc')->select();
		}
		
		public function updateOrderItem($data, $id){
			$this->where('id='.$id)->save($data);
		}
		
		public function getExistingOrderItem($itemId, $itemSize, $orderId){
			$map['itemId'] = $itemId;
			$map['itemSize'] = $itemSize;
			$map['orderId'] = $orderId;
			return $this->where($map)->select();
		}
		
		public function changeQuantity($record, $changedQuantity, $changedPrice){
			$data['quantity'] = $record['quantity'] + $changedQuantity;
			$data['price'] = round($record['price'] + $changedPrice, 2);
			$data['updatedDate'] = $record['updatedDate'];
			$this->updateOrderItem($data, $record['id']);
		}
		
		public function calculateExcludedItemsFee($orderId){
			$map["orderId"] = $orderId;
			//商品处于已支付，或者已申请退款，但商户尚未同意.非这二种状态的商品要被去掉
			$map['status'] = array('NOT IN',array('P', 'C1'));
			$items = $this->where($map)->select();
			$excludedFee = 0;
			foreach($items as $item){
				$excludedFee += $item['price'];
			}
			return $excludedFee;
		}

		public function confirmDelivery($orderId, $updatedDate) {
			$map["orderId"] = $orderId;
			$items = $this->where($map)->select();
			for ($i = 0; $i < count($items); $i++) {
				$id = $items[$i]["id"];
				if ($items[$i]["status"] == "P") {
					$data["id"] = $id;
					$data["status"] = "D";
					$data["updatedDate"] = $updatedDate;
					if ($this->save($data) === false) {
						return false;
					}
				}
			}
			return true;
		}

		public function confirmReceive($orderId, $updatedDate) {
			$map["orderId"] = $orderId;
			$items = $this->where($map)->select();
			for ($i = 0; $i < count($items); $i++) {
				$id = $items[$i]["id"];
				if ($items[$i]["status"] == "D") {
					$data["id"] = $id;
					$data["status"] = "V";
					$data["updatedDate"] = $updatedDate;
					if ($this->save($data) === false) {
						return false;
					}
				}
			}
			return true;
		}
		
		public function updateOrderItemsStatus($orderId, $fromStatus, $toStatus, $lastUpdatedDate) {
			$map["orderId"] = $orderId;
			$items = $this->where($map)->select();
			for ($i = 0; $i < count($items); $i++) {
				if($items[$i]['status'] != $fromStatus){
					continue;
				}
				$id = $items[$i]["id"];
				$data["id"] = $id;
				$data["status"] = $toStatus;
				$data["updatedDate"] = $lastUpdatedDate;
				if ($this->save($data) === false) {
					return false;
				}
			}
			return true;
		}

		public function cancelSingleOrderItem($id) {
			// payment goes first
			// if payment fail, return false
			// else continue

			$map["id"] = $id;
			$orderItemInformation = current($this->where($map)->select());
			$status = $orderItemInformation["status"];
			if ($status != "C1") {
				return false;
			}
			$data["id"] = $id;
			$data["status"] = "C2";
			$data["updatedDate"] = date("Y-m-d H:i:s" ,time());
			return ($this->save($data) !== false);
		}

		public function getOrderItemInformationById($id) {
			$map["id"] = $id;
			return current($this->where($map)->select());
		}
	}

?>