<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// TypeEvent.class.php 2013-02-27
namespace Starball\Event;
class TypeEvent{

	//登录成功，获取新浪微博用户信息
	public function sina($token){
		import("Org.ThinkSDK.ThinkOauth");
		$sina = \ThinkOauth::getInstance('sina', $token);
		$data = $sina->call('users/show', "uid={$sina->openid()}");

		if($data['error_code'] == 0){
			$userInfo['type'] = 'SINA';
			$userInfo['name'] = $data['name'];
			$userInfo['nick'] = $data['screen_name'];
			$userInfo['head'] = $data['avatar_large'];
			return $userInfo;
		} else {
			throw_exception("获取新浪微博用户信息失败：{$data['error']}");
		}
	}
	
 	//登录成功，微信用户信息
    public function weixin($token){
        $weixin   = \ThinkOauth::getInstance('weixin', $token);
        $data = $weixin->call('sns/userinfo');

        if($data['ret'] == 0){
            $userInfo['type'] = 'WEIXIN';
            $userInfo['name'] = $data['nickname'];
            $userInfo['nick'] = $data['nickname'];
            $userInfo['head'] = $data['headimgurl'];
            return $userInfo;
        } else {
            throw_exception("获取微信用户信息失败：{$data['errmsg']}");
        }
    }
	
 	//登录成功，facebook用户信息
    public function facebook($token){
        $facebook   = \ThinkOauth::getInstance('facebook', $token);
        $data = $facebook->call('');//需要查询facebook的接口说明

        //所有data的索引需要查询facebook的接口说明
        if($data[''] == 0){
            $userInfo['type'] = 'FACEBOOK';
            $userInfo['name'] = $data[''];
            $userInfo['nick'] = $data[''];
            $userInfo['head'] = $data[''];
            return $userInfo;
        } else {
            throw_exception("获取FACEBOOK用户信息失败：{$data['']}");
        }
    }

}