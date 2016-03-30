<?php
	namespace Common\Logic;
	use Common\Model\OrderModel;
	class OrderLogic extends OrderModel{
		public function getOrderByItemId($itemId, $status){
			$map['itemId'] = $itemId;
			$map['status'] = $status;
			$data = $this->where($map)->select();
			return $data;
		}
	}

?>