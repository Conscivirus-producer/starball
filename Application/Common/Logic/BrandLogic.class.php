<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/3/30
 * Time: 下午1:59
 */
namespace Common\Logic;
use Common\Model\BrandModel;
class BrandLogic extends BrandModel{
    public function getAllBrandInfo() {
        $dbData = $this->where()->order('brandName')->select();
        return $dbData;
    }

    public function getBrandIdByBrandName($brandName) {
        $map["brandName"] = $brandName;
        $data = $this->where($map)->select();
        return $data[0]["brandId"];
    }

    public function deleteBrandById($brandId) {
        $map["brandId"] = $brandId;
        return ($this->where($map)->delete() !== false);
    }

    public function addBrand($data) {
        return ($this->add($data) !== false);
    }

}