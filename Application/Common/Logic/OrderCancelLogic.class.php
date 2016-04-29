<?php
	namespace Common\Logic;
	use Common\Model\OrderCancelModel;
	class OrderCancelLogic extends OrderCancelModel{
		public function getCancelOrdersByConditions($conditions) {
			$orderLogic = D("Order", "Logic");
			$map = array();
			$createdDateStart = "2014-10-02";
			if ($conditions["createdDateStart"] != "") {
				$createdDateStart = $conditions["createdDateStart"];
			}
			$createdDateEnd = date("Y-m-d H:i:s" ,time());
			if ($conditions["createdDateEnd"] != "") {
				$createdDateEnd = $conditions["createdDateEnd"];
			}
			$map["createdDate"] = array('between',array($createdDateStart,$createdDateEnd));
			if ($conditions["status"] != "nothing") {
				$map["status"] = $conditions["status"];
			}
			$data = $this->where($map)->order('createdDate desc')->select();
			return $data;
		}

		public function getCancelOrderDetailedInformation($cancelId) {
			$orderItemLogic = D("OrderItem", "Logic");
			$map["cancelId"] = $cancelId;
			$data = current($this->where($map)->select());
			$orderItemId = $data["orderItemId"];
			$data["orderItemInformation"] = $orderItemLogic->getOrderItemInformationById($orderItemId);
			return $data;
		}

		public function agreeCancelOrder($cancelId) {
			$map["cancelId"] = $cancelId;
			$cancelOrderInformation = current($this->where($map)->select());
			if ($cancelOrderInformation["status"] != "N") {
				return false;
			}
			$updateData["cancelId"] = $cancelId;
			$updateData["status"] = "A";
			$updateData["lastUpdatedDate"] = date("Y-m-d H:i:s" ,time());
			return ($this->save($updateData) !== false);
		}

		public function disagreeCancelOrder($cancelId) {
			$map["cancelId"] = $cancelId;
			$cancelOrderInformation = current($this->where($map)->select());
			if ($cancelOrderInformation["status"] != "N") {
				return false;
			}
			$updateData["cancelId"] = $cancelId;
			$updateData["status"] = "C";
			$updateData["lastUpdatedDate"] = date("Y-m-d H:i:s" ,time());
			return ($this->save($updateData) !== false);
		}

		public function cancelOrderVerifyReceive($cancelId) {
			$map["cancelId"] = $cancelId;
			$cancelOrderInformation = current($this->where($map)->select());
			if ($cancelOrderInformation["status"] != "A") {
				return false;
			}
			$updateData["cancelId"] = $cancelId;
			$updateData["status"] = "V";
			$updateData["lastUpdatedDate"] = date("Y-m-d H:i:s" ,time());
			return ($this->save($updateData) !== false);
		}

		public function cancelOrderRefund($cancelId) {
			$map["cancelId"] = $cancelId;
			$cancelOrderInformation = current($this->where($map)->select());
			if ($cancelOrderInformation["status"] != "V") {
				return false;
			}
			$updateData["cancelId"] = $cancelId;
			$updateData["status"] = "D";
			$updateData["lastUpdatedDate"] = date("Y-m-d H:i:s" ,time());
			return ($this->save($updateData) !== false);
		}
	}

?>