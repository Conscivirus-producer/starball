<?php
	namespace Common\Logic;
	use Common\Model\ItemSubscriptionModel;
	class ItemSubscriptionLogic extends ItemSubscriptionModel{
		public function createRecord($email, $itemId){
			logInfo('fk11');
			$data['email'] = $email;
			$data['itemId'] = $itemId;
			$data['createdDate'] = date('y-m-d h:i:s',time());
			logInfo('fk22');
			return $this->add($data);
		}
		
	}
