<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/6/3
 * Time: 上午11:16
 */
namespace Common\Logic;
use Common\Model\AdminUserModel;
class AdminUserLogic extends AdminUserModel{
    public function checkUserExistence($data) {
        $map["username"] = $data["username"];
        $map["password"] = $data["password"];
        if ($this->where($map)->Count() >= 1) {
            return true;
        } else {
            return false;
        }
    }
}