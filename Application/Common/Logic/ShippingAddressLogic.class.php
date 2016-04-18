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
	}

?>