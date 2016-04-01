<?php
	namespace Common\Logic;
	use Common\Model\OrderModel;
	class OrderLogic extends OrderModel{
		public function getOrderByUserId($userId, $status){
			$map['userId'] = $userId;
			$map['status'] = $status;
			$data = $this->where($map)->select();
			return $data;
		}
		
		public function updateOrder($data, $orderId){
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$this->where('orderId='.$orderId)->save($data);
		}
	}

?>