<?php
namespace Admin\Behaviors;
use Think\Controller;
class CheckLoginBehavior extends \Think\Behavior{
    //行为执行入口
    public function run(&$param){
    	if(session('super_userid') == ''){
    		//redirect("/thinkphp/ErrorHandling/ErrorHandling/error/message/非法访问");
			//redirect(C('root_folder_name'). "/ErrorHandling/ErrorHandling/error/message/没有登录");
			header("Content-type: text/html; charset=utf-8");
			//exit('请登录操作');
    	}
    }
}