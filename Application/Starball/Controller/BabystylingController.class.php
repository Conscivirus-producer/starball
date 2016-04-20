<?php
namespace Starball\Controller;
use Think\Controller;
class BabystylingController extends BaseController {
	public function index(){
		$this->commonProcess();
		$this->display();
	}
}