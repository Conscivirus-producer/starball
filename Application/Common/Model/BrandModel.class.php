<?php
	namespace Common\Model;
	use Think\Model;
	class BrandModel extends Model {
		protected $trueTableName = 't_brand';
		public function getBrand(){
			$brand = D('Brand')->order('brandId desc')->select();
			return $brand;
		}
	}
?>