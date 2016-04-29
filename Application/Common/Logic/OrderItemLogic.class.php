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
		}
		
	}

?>