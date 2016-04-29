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
		
		public function getOrderItemsById($id){
			return $this->where('id='.$id)->order('updatedDate desc')->select();
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
		
		public function updateOrderItemStatusByOrder($orderNumber, $status){
			$map['orderNumber'] = $orderNumber;
			$data['status'] = $status;
			$this->where($map)->save($data);
		}

		public function confirmDelivery($orderId, $updatedDate) {
			$map["orderId"] = $orderId;
			$items = $this->where($map)->select();
			for ($i = 0; $i < count($items); $i++) {
				$id = $items[$i]["id"];
				if ($items[$i]["status"] == "p") {
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

		public function cancelEntireOrder($orderId, $updatedDate) {
			$map["orderId"] = $orderId;
			$items = $this->where($map)->select();
			for ($i = 0; $i < count($items); $i++) {
				$id = $items[$i]["id"];
				$data["id"] = $id;
				$data["status"] = "C2";
				$data["updatedDate"] = $updatedDate;
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
	}

?>