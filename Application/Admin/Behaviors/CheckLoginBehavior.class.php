<?php
namespace Admin\Behaviors;
use Think\Controller;
class CheckLoginBehavior extends \Think\Behavior{
    //行为执行入口
    public function run(&$params){
		$uriArray = split ("/", $_SERVER["REQUEST_URI"]);
		$part = end($uriArray);
		if ($part != "login" && $part != "doLogin" && session('starball_kid_username_fuck_you_hacker') == '') {
			header("Content-type: text/html; charset=utf-8");
			$url = U('Home/login');
			header('Location: '.$url);
			exit("请登录后再操作!");
    	}
    }
}