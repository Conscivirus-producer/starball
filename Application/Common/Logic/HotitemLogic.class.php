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

		public function insertOneHotItem($data) {
			$map["type"] = $data["type"];
			$sequence = (int)current($this->where($map)->order("sequence desc")->select())["sequence"] + 1;
			$sequence = "".$sequence;
			$data["sequence"] = $sequence;
			$data["lastUpdatedDate"] = date('y-m-d h:i:s',time());
			return ($this->add($data) !== false);
		}

		public function getHotItemInformationById($hotId) {
			$map["hotId"] = $hotId;
			$data = $this->where($map)->select();
			return current($data);
		}

		public function updateOneHotItem($data) {
			$imageChanged = $data["imageChanged"];
			if ($imageChanged == "0") {
				unset($data["imageChanged"]);
				return ($this->save($data) !== false);
			} else {
				$map["hotId"] = $data["hotId"];
				$imageUrl = current($this->where($map)->select())["image"];
				$imageKey = end(split("/", $imageUrl));
				$imageLogic = D("Image", "Logic");
				return (($imageLogic->deleteImageByQiniuKey($imageKey) !== false) & ($this->save($data) !== false));
			}
		}
	}


?>