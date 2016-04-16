<?php
namespace Starball\Controller;
use Think\Controller;
class UserController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => I('tab')));
		}
		$this->assign('tab', I('tab'));
		$this->display();
	}
	
}
