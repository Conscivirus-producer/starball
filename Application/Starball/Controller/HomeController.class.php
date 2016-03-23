<?php
namespace Starball\Controller;
use Think\Controller;
class HomeController extends BaseController {

	protected function pageDisplay(){
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
	}	
}