<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/18
 * Time: 下午1:19
 */
namespace Common\Logic;
use Common\Model\UserModel;
class UserLogic extends UserModel{
    public function getUserInformationByUserId($userId) {
        $map["userId"] = $userId;
        return current($this->where($map)->select());
    }
}

?>