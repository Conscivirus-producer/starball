<?php
namespace Starball\Controller;
use Think\Controller;
class HomeController extends BaseController {
	
	public function index(){
		$this->commonProcess();
    	$hotitemLogic = D("Hotitem", "Logic");
    	$headArea = $hotitemLogic->getHomePageItems("H");
		$middleLeftHead = $hotitemLogic->getHomePageItems("MLH");
		$middleLeftFoot = $hotitemLogic->getHomePageItems("MLF");
		$middleRight = $hotitemLogic->getHomePageItems("MR");
		$footArea = $hotitemLogic->getHomePageItems("F");
		$this->assign('headArea', $headArea);
		$this->assign('middleLeftHead', $middleLeftHead);
		$this->assign('middleLeftFoot', $middleLeftFoot[0]);
		$this->assign('middleRight', $middleRight);
		$this->assign('footArea', $footArea);
		$this->display();
	}
	
	public function register($fromAction){
		session('fromAction', $fromAction);
		$this->commonProcess();
		$this->display();
	}
}