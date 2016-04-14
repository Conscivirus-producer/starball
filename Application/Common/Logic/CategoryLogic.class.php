<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/3/30
 * Time: 下午1:27
 */
namespace Common\Logic;
use Common\Model\CategoryModel;
class CategoryLogic extends CategoryModel{
    public function getAllCategoryInfo() {
        $dbData = $this->where()->select();
        return $dbData;
    }

    public function getCategoryIdByCategoryName($categoryName) {
        $map["categoryName"] = $categoryName;
        $data = $this->where($map)->select();
        return $data[0]["categoryId"];
    }
}