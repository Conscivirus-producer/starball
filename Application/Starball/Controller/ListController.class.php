<?php
namespace Starball\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
    	$this->display();
    }
	
	public function byTimeRange($range, $page=1){
		$itemList = D('Item')->where()->select();
		$this->display('index');
	}
}