<?php
	namespace Common\Logic;
	use Common\Model\ItemPriceModel;
	class ItemPriceLogic extends ItemPriceModel{
		public function getPriceByItemId($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->select();
			return $data;
		}
	}

?>