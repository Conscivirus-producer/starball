<?php
	namespace Common\Logic;
	use Common\Model\ImageModel;
	use Qiniu\Auth;
	use Qiniu\Storage\BucketManager;
	class ImageLogic extends ImageModel{
		public function getImageById($itemId){
			$map['itemId'] = $itemId;
			$data = $this->where($map)->order('sequence')->select();
			return $data;
		}
		public function insertMultipleImages($itemId, $imageArray) {
			$count = count($imageArray);
			for ($i = 0; $i < $count; $i++) {
				$data["itemId"] = $itemId;
				$data["image"] = $imageArray[$i];
				$data["sequence"] = $i;
				$index = $this->add($data);
				if ($index == false) {
					return false;
				}
			}
			return true;
		}
		public function updateOneItemImages($itemId, $imageArray) {
			$map['itemId'] = $itemId;
			if ($this->where($map)->delete() == false) {
				return false;
			} else {
				return $this->insertMultipleImages($itemId, $imageArray);
			}
		}
		public function getQiniuKeyByImageId($imageId) {
			$map["imageId"] = $imageId;
			$data = $this->where($map)->select();
			if (count($data) == 0) {
				return "";
			} else {
				$imageUrl = $data[0]["image"];
				$dataArray = split("/", $imageUrl);
				return $dataArray[3];
			}
		}
		public function deleteImageByQiniuKey($key) {
			vendor("qiniusdk.autoload");
			$accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
			$secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
			//初始化Auth状态
			$auth = new Auth($accessKey, $secretKey);

			//初始化BucketManager
			$bucketMgr = new BucketManager($auth);
			$bucket = 'image';
			if ($key == "") {
				return false;
			} else {
				$err = $bucketMgr->delete($bucket, $key);
				if ($err !== null) {
					return false;
				} else {
					return true;
				}
			}
		}
		public function deleteOneImageByImageId($imageId) {
			$map["imageId"] = $imageId;

			$key = $this->getQiniuKeyByImageId($imageId);
			if ($this->deleteImageByQiniuKey($key) == false) {
				return false;
			} else {
				if ($this->where($map)->delete() != false) {
					return true;
				}
				return false;
			}
		}
		public function deleteImagesByItemId($itemId) {
			$map["itemId"] = $itemId;
			$images = $this->where($map)->select();
			for ($i = 0; $i < count($images); $i++) {
				$imageId = $images[$i]["imageId"];
				if ($this->deleteOneImageByImageId($imageId) === false) {
					return false;
				}
			}
			return true;
		}
	}


?>