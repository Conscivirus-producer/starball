<?php
namespace Starball\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function index(){
    	$this->commonProcess();
		$this->pageDisplay();
    	$this->display();
    }
	
	protected function commonProcess(){
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
}