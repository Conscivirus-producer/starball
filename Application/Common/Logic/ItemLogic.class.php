<?php
	namespace Common\Logic;
	use Common\Model\ItemModel;
	class ItemLogic extends ItemModel{
		public function getItemById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
	}


?>