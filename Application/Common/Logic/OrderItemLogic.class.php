<?php
	namespace Common\Logic;
	use Common\Model\OrderItemModel;
	class OrderItemLogic extends OrderItemModel{
		public function create($data){
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$this->add($data);	
		}
		
		public function getOrderItemsById($id){
			return $this->where('id='.$id)->order('updatedDate desc')->select();
		}
		
		public function getOrderItemsByOrdeNumber($orderNumber){
			return $this->where('orderNumber='.$orderNumber)->order('updatedDate desc')->select();
		}
		
		public function updateOrderItem($data, $id){
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$this->where('id='.$id)->save($data);
		}
		
		public function getExistingOrderItem($itemId, $itemSize, $orderNumber){
			$map['itemId'] = $itemId;
			$map['itemSize'] = $itemSize;
			$map['orderNumber'] = $orderNumber;
			return $this->where($map)->select();
		}
		
		public function addQuantity($record){
			$data['quantity'] = $record['quantity'] + 1;
			$data['price'] = $record['price'] + $record['price'] / $record['quantity'];
			$this->updateOrderItem($data, $record['id']);
		}
		
		public function minusQuantity($record){
			if($data['quantity'] == 0){
				return;
			}
			$data['quantity'] = $record['quantity'] - 1;
			$this->updateOrderItem($data, $record['id']);
		}		
	}

?>