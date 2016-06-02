<?php
	namespace Common\Logic;
	use Common\Model\SupportingDataModel;
	class SupportingDataLogic extends SupportingDataModel{
		
		public function getValueByKey($key){
			$map['key'] = $key;
			$data = $this->where($map)->find();
			return $data['value'];
		}

		public function getAllKeyAndValues() {
			return $this->where()->select();
		}

		public function create() {
			$this->display();
		}

		public function checkKeyExistence($key) {
			$map["key"] = $key;
			$count = $this->where($map)->Count();
			if ($count >= 1) {
				return true;
			} else {
				return false;
			}
		}

		public function insertOneKeyAndValue($data) {
			return ($this->add($data) !== false);
		}

		public function getAllInformationByKey($key) {
			$map['key'] = $key;
			$data = current($this->where($map)->select());
			return $data;
		}

		public function updateOneKeyAndValue($data) {
			return ($this->save($data) !== false);
		}
	}

?>