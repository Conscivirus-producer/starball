<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/18
 * Time: 下午1:19
 */
namespace Common\Logic;
use Common\Model\UserSocialMediaModel;
class UserSocialMediaLogic extends UserSocialMediaModel{
	public function findByOpenId($openid){
		$map['openid'] = $openid;
		return $this->where($map)->find();
	}
	
	public function updateByOpenId($data, $openid){
		$map['openid'] = $openid;
		$this->where($map)->save($data);
	}
}
?>
	