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
        $dbData = $this->where()->select();
        return $dbData;
    }
}