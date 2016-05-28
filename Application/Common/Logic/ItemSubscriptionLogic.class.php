<?php
	namespace Common\Logic;
	use Common\Model\ItemSubscriptionModel;
	class ItemSubscriptionLogic extends ItemSubscriptionModel{
		public function createRecord($email, $itemId){
			$data['email'] = $email;
			$data['itemId'] = $itemId;
			$data['createdDate'] = date('y-m-d h:i:s',time());
			$data['lastUpdatedDate'] = date('y-m-d h:i:s',time());
			return $this->add($data);
		}
		
		public function queryByItemId($itemId){
			$map['itemId'] = $itemId;
			return $this->where($map)->select();
		}
		
		public function batchUpdateStatus($subscriptionIds){
			$map['subscriptionId'] = array('in', $subscriptionIds);
			$data['status'] = '1';
			$data['lastUpdatedDate'] = date('y-m-d h:i:s',time());
			$this->where($map)->save($data);
		}
		
	}
