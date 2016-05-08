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
    public function getUserInformationByMap($map) {
        if ($this->where($map)->count() >= 1) {
            return current($this->where($map)->select());
        } else {
            return false;
        }
    }
	
	public function updateUserInformation($data, $userId){
		$map['userId'] = $userId;
		$data['lastUpdatedDate'] = date("Y-m-d H:i:s" ,time());
		$this->where($map)->save($data);
	}
}

?>