<?php
namespace Starball\Controller;
use Think\Controller;
class UserController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		$this->assign('tab', I('tab'));
		$this->display();
	}
	
}
