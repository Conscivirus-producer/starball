<?php
	namespace Common\Logic;
	use Common\Model\ImageModel;
	class ImageLogic extends ImageModel{
		public function getImageById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
	}


?>