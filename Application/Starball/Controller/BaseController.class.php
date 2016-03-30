<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function prepareUserSetting(){
		if(cookie('think_language') == 'zh-cn' && cookie('preferred_currency') == ''){
			cookie('preferred_currency','CNY',3600);
		}
		if(I('currency') == 'CNY'){
			cookie('preferred_currency','CNY',3600);
		}else if(I('currency') == 'HKD'){
			cookie('preferred_currency','HKD',3600);
		}
	}
	
	protected function prepareBrandList(){
		$this->assign('brandList', D('Brand')->select());
	}
	
	protected function prepareUserMenu(){
		$itemLogic = D("Item", "Logic");
		foreach (C('USERTYPE') as $key => $value){
			$this->assign($key.'MenuBrand', $itemLogic->getBrandNameListByGrade($value[0], $value[1]));				
			$this->assign($key.'MenuCategory', $itemLogic->getCategoryNameByGrade($value[0], $value[1]));
		}
	}
	
	protected function prepareShoppingAndFavoriteList(){
		if(session('userName') == ''){
			$shoppingList = session('shoppingList');
			$favoriteList = session('favoriteList');
			//$this->assign('shoppingList',$shoppingList);
			//$this->assign('favoriteList',$favoriteList);
		}
		//log testing
		/*foreach(session('shoppingList') as $itemId=>$subarray){
			foreach($subarray as $itemSize=>$quantity){
				logInfo("shoppingItemList: itemId:".$itemId.",itemSize:".$itemSize.",quantity:".$quantity);
			}
	 		foreach(session('favoriteList') as $value){
				logInfo('favoriteItemList:'.$value);
			}
		}*/
	}
	
	protected function commonProcess(){
		$this->prepareUserSetting();
		$this->prepareBrandList();
		$this->prepareUserMenu();
		$this->prepareShoppingAndFavoriteList();
		if(IS_POST){
			if(I('method') == 'register'){
				$this->register();
			}else if(I('method') == 'login'){
				$this->login();
			}else if(I('method') == 'logout'){
				$this->logout();
			}
		}
	}
	
	//abstract protected function pageDisplay();	
	
	private function register(){
		$user = D("User");
		if(!$data = $user->create()){
            // 防止输出中文乱码
            header("Content-type: text/html; charset=utf-8");
            exit($user->getError());
		}
		$userId = $user->add($data);
		session('userId', $userId);
		session('userName', I('userName'));
		session('email', I('email'));
	}
	
	private function logout(){
		session(null);
	}
	
	private function login(){
		$login = D('Login');
		
		if(!$data = $login->create()){
		    header("Content-type: text/html; charset=utf-8");
            exit($login->getError());
		}
		
		$where['userName'] = $data['userName'];
		$where['email'] = $data['userName'];
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		
		$map['password'] = $data['password'];
		$result = $login->where($map)->find();
		if($result){
			session('userId', $result['id']);
			session('email', $result['email']);
			session('userName', $result['userName']);
			session('lastDate', $result['lastDate']);
			session('lastIp', $result['lastIp']);
			//$this->assign('userName', $result['userName']);
		} else{
			$this->error("用户名密码不正确");
		}
	}

	public function addToFavoriteList(){
		if(session('userName') == ''){
			$this->addFavoriteListToSession();
		}else{
			
		}
		$data = array(
		    'data'=>'吃饼饼',
		    'message'=>'处理成功',
		);
		$vo = $data;
		$vo['status'] = 1;
		$this->ajaxReturn($vo, "json");
	}

	private function addFavoriteListToSession(){
		$itemId = I('itemId');
		if(session('favoriteList') == ''){
			session('favoriteList',array($itemId));
		}else{
			$itemList = session('favoriteList');
			if(!in_array($itemId, $itemList)){
				array_push($itemList, $itemId);
				session('favoriteList',$itemList);
			}
		}
	}
	
	protected function getSizeDescriptionByAge($age){
		$sizeArray = C('ITEMSIZE');
		if(strpos($age, ',') <= 0){
			return $sizeArray[$age][0].'  ('.$sizeArray[$age][1].' - '.$sizeArray[$age][2].'cm)';
		}else{
			$startAge = current(explode(',', $age));
			$endAge = end(explode(',', $age));
			return $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)';
		}
	}
}