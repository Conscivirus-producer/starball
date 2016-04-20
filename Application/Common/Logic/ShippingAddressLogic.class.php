<?php
	namespace Common\Logic;
	use Common\Model\ShippingAddressModel;
	class ShippingAddressLogic extends ShippingAddressModel{
		
		public function updateAddress($data, $addressId){
			$map['addressId'] = $addressId;
			$this->where($map)->save($data);
		}
		
		public function findExsitingAddress($addressId){
			$map['addressId'] = $addressId;
			return $this->where($map)->find();
		}
		
		public function getAllAddress($userId){
			$map['userId'] = $userId;
			return $this->where($map)->select();
		}
		
		public function setDefault($userId, $addressId){
			$map['userId'] = $userId;
			$map['addressId'] = $addressId;
			$result = $this->where($map)->find();
			if($result != ''){
				$result['default'] = 1;
				$this->save($result);
			}
		}
		
		public function unsetDefault($userId){
			$map['userId'] = $userId;
			$map['default'] = 1;
			$result = $this->where($map)->find();
			if($result != ''){
				$result['default'] = 0;
				$this->save($result);
			}
		}
	}

?>