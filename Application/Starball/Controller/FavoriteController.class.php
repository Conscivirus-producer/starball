<?php
namespace Starball\Controller;
use Think\Controller;
class FavoriteController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		$this->display();
	}
	
	public function removeFavorite(){
		if(!$this->isLogin()){
			$this->removeFavoriteFromSession();
		}else{
			$this->removeFavoriteFromUser();
		}
		$this->redirect('Favorite/index');
	}
	
	private function removeFavoriteFromSession(){
		$favoriteList = session('favoriteList');
		$i = 0;
		foreach($favoriteList as $record){
			if($record['itemId'] == I('itemId')){
				unset($favoriteList[$i]);
				break;
			}
			$i++;
		}
		session('favoriteList', $favoriteList);
	}
	
	private function removeFavoriteFromUser(){
		D('FavoriteItem', 'Logic')->removeFavorite($this->getCurrentUserId(), I('itemId'));
	}
}
	