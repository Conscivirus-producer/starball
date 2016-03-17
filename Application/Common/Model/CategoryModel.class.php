<?php
	namespace Common\Model;
	use Think\Model;
	class CategoryModel extends Model {
		protected $trueTableName = 't_category';
		public function getCategory(){
			$category = D('Category');
			$mainCategory = $category->where('parentCategoryId = 0')->order('categoryId desc')->select();
			$subCategroy = $category->where('parentCategoryId != 0')->order('categoryId desc')->select();
			for ($i=0; $i < count($mainCategory); $i++) {
				$subs = array();
				for ($j=0; $j < count($subCategroy) ; $j++) { 
					if($subCategroy[$j]['parentCategoryId'] == $mainCategory[$i]['categoryId']){
						array_push($subs, $subCategroy[$j]);
					}
				}
				$mainCategory[$i]['subCategories'] = $subs; 
			}
			return $mainCategory;
		}
	}
?>