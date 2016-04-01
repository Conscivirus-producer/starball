<?php
	namespace Common\Logic;
	use Common\Model\InventoryModel;
	class InventoryLogic extends InventoryModel{
		public function getInventoryByItemId($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
		
		public function getSizeDescriptionById($id){
			$map['id'] = $id;
			$data = $this->where($map)->select();
			return getSizeDescriptionByAge($data[0]['age']);
		}
	}


?>