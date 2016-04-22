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

		public function getOrderInformationWithUserInformation($map) {
			$newMap = array();
			$createdDateStart = "2014-10-02";
			if ($map["createdDateStart"] != "") {
				$createdDateStart = $map["createdDateStart"];
			}
			$createdDateEnd = date("Y-m-d" ,time());
			if ($map["createdDateEnd"] != "") {
				$createdDateEnd = $map["createdDateEnd"];
			}
			$newMap["createdDate"] = array('between',array($createdDateStart,$createdDateEnd));
			if ($map["status"] != "nothing") {
				$newMap["status"] = $map["status"];
			}
			if ($map["isGiftPackage"] != "nothing") {
				$newMap["isGiftPackage"] = $map["isGiftPackage"];
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
			$map["orderId"] = $orderId;
			$information = current($this->where($map)->select());
			$information["orderItems"] = $orderItemLogic->getOrderItemsByOrdeId($orderId);
			$shippingAddressId = $information["shippingAddress"];
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

	}

?>