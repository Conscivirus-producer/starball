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
		
		public function getCurrentOutstandingOrder($userId, $status){
			$map['userId'] = $userId;
			$map['status'] = $status;
			$data = $this->where($map)->find();
			return $data;
		}
		
		public function queryOrder($map){
			return $this->where($map)->order('createdDate desc')->select();
		}
		
		public function updateOrder($data, $orderId){
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$map['orderId'] = $orderId;
			$this->where($map)->save($data);
		}
		
		public function updateOrderByNumber($data, $orderNumber){
			$data['updatedDate'] = date("Y-m-d H:i:s" ,time());
			$map['orderNumber'] = $orderNumber;
			$this->where($map)->save($data);
		}
	}

?>