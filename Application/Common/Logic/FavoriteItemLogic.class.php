<?php
	namespace Common\Logic;
	use Common\Model\FavoriteItemModel;
	class FavoriteItemLogic extends FavoriteItemModel{
		public function addFavorite($data){
			$map['userId'] = $userId;
			$map['itemId'] = $itemId;
			$map['status'] = '1';
			$existingRecord = $this->where($map)->find();
			if($existingRecord == ''){
				$data['createdDate'] = date('y-m-d h:i:s',time());
				$data['updatedDate'] = date('y-m-d h:i:s',time());
				$this->add($data);
			}else{
				$newMap['id'] = $existingRecord['id'];
				$data['updatedDate'] = date('y-m-d h:i:s',time());
				$this->where($newMap)->save($data);
			}
		}
		
		public function getFavoriteList($userId){
			$map['userId'] = $userId;
			$map['status'] = '1';
			return $this->where($map)->order('updatedDate desc')->select();
		}
		
		public function removeFavorite($userId, $itemId){
			$map['userId'] = $userId;
			$map['itemId'] = $itemId;
			$map['status'] = '1';
			
			$data['status'] = '0';
			$data['updatedDate'] = date('y-m-d h:i:s',time());
			$this->where($map)->save($data);			
		}
	}
		