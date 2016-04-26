<?php
namespace Starball\Controller;
use Think\Controller;
class ThirdController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		$this->display();
	}
	
}