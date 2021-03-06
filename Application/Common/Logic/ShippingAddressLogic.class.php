<?php
	namespace Common\Logic;
	use Common\Model\ShippingAddressModel;
	class ShippingAddressLogic extends ShippingAddressModel{
		
		public function updateAddress($data, $addressId){
			$map['addressId'] = $addressId;
			$this->where($map)->save($data);
		}
		
		public function deleteAddress($addressId){
			$map['addressId'] = $addressId;
			$this->where($map)->delete();			
		}
		
		public function findExsitingAddress($addressId){
			$map['addressId'] = $addressId;
			return $this->where($map)->find();
		}
		
		public function getDefaultAddress($userId){
			$map['userId'] = $userId;
			$map['default'] = '1';
			return $this->where($map)->find();
		}
		
		public function getAllAddress($userId){
			$map['userId'] = $userId;
			return $this->where($map)->select();
		}
		
		public function setDefault($addressId){
			$map['addressId'] = $addressId;
			$result = $this->where($map)->find();
			$data['default'] = 1;
			$this->where($map)->save($data);
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