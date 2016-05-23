<?php
	namespace Common\Logic;
	use Common\Model\SupportingDataModel;
	class SupportingDataLogic extends SupportingDataModel{
		
		public function getValueByKey($key){
			$map['key'] = $key;
			$data = $this->where($map)->find();
			return $data['value'];
		}
		
	}

?>