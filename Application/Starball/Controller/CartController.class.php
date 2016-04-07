<?php
namespace Starball\Controller;
use Think\Controller;
class CartController extends BaseController {
	public function index(){
		$this->testLogShoppingList();
		$this->commonProcess();
		$this->display();
	}
	
}