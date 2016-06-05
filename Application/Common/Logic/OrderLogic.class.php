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
		
		public function findByOrderNumber($orderNumber){
			$map['orderNumber'] = $orderNumber;
			return $this->where($map)->find();
		}
		
		public function findByOrderId($orderId){
			$map['orderId'] = $orderId;
			return $this->where($map)->find();			
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
		
		public function getOrderInformationWithUserInformation($map) {
			$newMap = array();
			$createdDateStart = "2014-10-02";
			if ($map["createdDateStart"] != "") {
				$createdDateStart = $map["createdDateStart"];
			}
			$createdDateEnd = date("Y-m-d H:i:s" ,time());
			if ($map["createdDateEnd"] != "") {
				$createdDateEnd = $map["createdDateEnd"];
			}
			$newMap["createdDate"] = array('between',array($createdDateStart,$createdDateEnd));
			if ($map["status"] != "nothing") {
				$newMap["status"] = $map["status"];
			}
			$userLogic = D("User", "Logic");
			$data = $this->where($newMap)->order('createdDate desc')->select();
			$res = array();
			$userMap["userName"] = array('like', "%".$map["userName"]."%");
			$userMap["email"] = array('like', "%".$map["email"]."%");
			$userMap["mobile"] = array('like', "%".$map["mobile"]."%");
			for ($i = 0; $i < count($data); $i++) {
				$temp = $data[$i];
				$userId = $temp["userId"];
				$userMap["userId"] = array('eq',$userId);
				$userInformation = $userLogic->getUserInformationByMap($userMap);
				if ($userInformation !== false) {
					$temp["userInformation"] = $userInformation;
					array_push($res, $temp);
				}
			}
			return $res;
		}

		public function getOrderInformationByOrderId($orderId) {
			$orderItemLogic = D("OrderItem", "Logic");
			$shippingAddressLogic = D("ShippingAddress", "Logic");
			$orderBillLogic = D("OrderBill", "Logic");
			$userLogic = D("User", "Logic");
			$map["orderId"] = $orderId;
			$information = current($this->where($map)->select());
			$information["orderItems"] = $orderItemLogic->getOrderItemsByOrdeId($orderId);
			$shippingAddressId = $information["shippingAddress"];
			$userId = $information["userId"];
			$information["userInformation"] = $userLogic->getUserInformationByUserId($userId);
			if ($shippingAddressId == "0") {
				$information["shippingAddress"] = "";
			} else {
				$information["shippingAddress"] = current($shippingAddressLogic->findExsitingAddress($shippingAddressId));
			}
			$orderNumber = $information["orderNumber"];
			if ($orderNumber == "") {
				$information["orderBills"] = "";
			} else {
				$orderBillMap["orderNumber"] = $orderNumber;
				$information["orderBills"] = $orderBillLogic->queryBill($orderBillMap);
				$information['payChannel'] = $information["orderBills"][0]['channel'];
			}
			return $information;
		}

		public function getOrderInformationByOrderNumber($orderNumber) {
			$map["orderNumber"] = $orderNumber;
			$orderId = $this->where($map)->getField("orderId");
			$orderItemLogic = D("OrderItem", "Logic");
			$shippingAddressLogic = D("ShippingAddress", "Logic");
			$orderBillLogic = D("OrderBill", "Logic");
			$information = current($this->where($map)->select());
			$information["orderItems"] = $orderItemLogic->getOrderItemsByOrdeId($orderId);
			$shippingAddressId = $information["shippingAddress"];
			if ($shippingAddressId == "0") {
				$information["shippingAddress"] = array();
			} else {
				$information["shippingAddress"] = $shippingAddressLogic->findExsitingAddress($shippingAddressId);
			}
			$orderBillMap["orderNumber"] = $orderNumber;
			$information["orderBills"] = $orderBillLogic->queryBill($orderBillMap);
			return $information;
		}

		public function confirmDelivery($data) {
			$orderItemLogic = D("OrderItem", "Logic");
			$orderId = $data["orderId"];
			$map["orderId"] = $orderId;
			$orderInformation = current($this->where($map)->select());
			$status = $orderInformation["status"];
			if ($status != "P") {
				return false;
			}
			$lastUpdatedDate = date("Y-m-d H:i:s" ,time());
			if ($orderItemLogic->confirmDelivery($orderId, $lastUpdatedDate) === false) {
				return false;
			}
			$updateData["orderId"] = $orderId;
			$updateData["updatedDate"] = $lastUpdatedDate;
			$updateData["status"] = "D";
			$updateData["shippingMethod"] = $data["expressName"];
			$updateData["shippingOrderNumber"] = $data["expressNumber"];
			if ($this->save($updateData) === false) {
				return false;
			}
			$userInfo["email"] = $data["email"];
			$userInfo["userName"] = $data["userName"];
			$mailContent = $this->getOrderInformationByOrderNumber($orderInformation['orderNumber']);
			$mailContent['expressName'] = $data["expressName"];
			$mailContent['expressNumber'] = $data["expressNumber"];
			return (sendMailNewVersion($mailContent, "delivered", $userInfo) !== false);
		}

		public function confirmReceive($orderId) {
			$orderItemLogic = D("OrderItem", "Logic");
			$map["orderId"] = $orderId;
			$orderInformation = current($this->where($map)->select());
			$orderStatus = $orderInformation["status"];
			if ($orderStatus != "D") {
				return false;
			}
			$lastUpdatedDate = date("Y-m-d H:i:s" ,time());
			if ($orderItemLogic->confirmReceive($orderId, $lastUpdatedDate) === false) {
				return false;
			}
			$updateData["orderId"] = $orderId;
			$updateData["updatedDate"] = $lastUpdatedDate;
			$updateData["status"] = "V";
			return ($this->save($updateData) !== false);
		}

		public function updateOrderStatus($orderId, $fromStatus, $toStatus) {
			$orderItemLogic = D("OrderItem", "Logic");
			$map["orderId"] = $orderId;
			$orderInformation = current($this->where($map)->select());
			$orderStatus = $orderInformation["status"];
			if ($orderStatus == $toStatus) {
				//如果是重复操作退款
				return true;
			}
			if ($orderStatus != $fromStatus) {
				return false;
			}
			// payment function to be added
			// payment first
			// if payment fail, return false
			// else continue
			$lastUpdatedDate = date("Y-m-d H:i:s" ,time());
			if ($orderItemLogic->updateOrderItemsStatus($orderId, $fromStatus, $toStatus, $lastUpdatedDate) === false) {
				return false;
			}
			$updateData["orderId"] = $orderId;
			$updateData["updatedDate"] = $lastUpdatedDate;
			$updateData["status"] = $toStatus;
			return ($this->save($updateData) !== false);
		}

		public function getOrderIdByOrderNumber($orderNumber) {
			$map["orderNumber"] = $orderNumber;
			$orderInformation = current($this->where($map)->select());
			return $orderInformation["orderId"];
		}

	}

?>