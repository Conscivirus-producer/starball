<?php
	namespace Common\Logic;
	use Common\Model\HotitemModel;
	class HotitemLogic extends HotitemModel{
		public function getHomePageItems($type){
			$map["type"] = array('eq',$type);
			$map["active"] = array('eq','1');
			$data = $this->where($map)->order('sequence')->select();
			return $data;
		}

		public function getHotItems($type) {
			$map["type"] = array('eq',$type);
			$data = $this->where($map)->order('sequence')->select();
			return $data;
		}
	}


?>