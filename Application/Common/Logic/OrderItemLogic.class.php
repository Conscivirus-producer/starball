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
			return $this->where('orderId='.$orderId)->order('updatedDate desc')->select();
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
		
		public function addQuantity($record){
			$data['quantity'] = $record['quantity'] + 1;
			$data['price'] = $record['price'] + $record['price'] / $record['quantity'];
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$this->updateOrderItem($data, $record['id']);
		}
		
		public function minusQuantity($record){
			if($data['quantity'] == 0){
				return;
			}
			$data['quantity'] = $record['quantity'] - 1;
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$this->updateOrderItem($data, $record['id']);
		}		
	}

?>