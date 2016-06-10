<?php
namespace Starball\Controller;
use Think\Controller;
class UserController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => I('tab')));
		}
		if(IS_POST){
			if(I('method') == 'changeUserInfo'){
				$this->changeUserInformation();
			}else if(I('method') == 'setUpUserInfo'){
				$this->setUpUserInfo();
			}else if(I('method') == 'changePassword'){
				$this->changePassword();
			}
		}
		$this->prepareUserInformation();
		$this->prepareUserAddressInformation();
		$this->prepareOrderList();
		$this->assign('tab', I('tab'));
		$this->display();
	}
	
	private function changeUserInformation(){
		$mobile = I('mobile');
		if(!preg_match('/^\d+$/i',$mobile)){
			$this->assign('message', L('mobileShouldbeNumber'));
		}else{
			$data['mobile'] = $mobile;
			$data['userId'] = $this->getCurrentUserId();
			D('User', 'Logic')->updateUserInformation($data, $this->getCurrentUserId());
			$this->assign('message', L('personalInfoChanged'));
		}
	}
	
	private function setUpUserInfo(){
		$userType = I('userType');
		if($userType == 'N'){
			$this->setUpNewUser();
		}else if($userType == 'E'){
			$this->bindExsitingUser();
		}
	}
	
	private function bindExsitingUser(){
		$email = I('email');
		$password = md5(I('newPwd'));
		$map['email'] = $email;
		$existingUser = D('User', 'Logic')->getUserInformationByMap($map);
		if($existingUser == false){
			$this->assign('message', L('emailNotExist'));
			return;
		}else{
			if($password != $existingUser['password']){
				$this->assign('message', L('existingPwdIncorrect'));
				return;
			}
			
			//删除掉临时用户id
			$tmpUserId = $this->getCurrentUserId();
			$newMap['userId'] = $tmpUserId;
			D('User', 'Logic')->where($newMap)->delete();
			
			//绑定现有用户id
			session("starballkids_userId", $existingUser['userId']);
			$data['userId'] = $existingUser['userId'];
			D('UserSocialMedia', 'Logic')->updateByOpenId($data, session('starballkids_social_openid'));
			$this->assign('message', L('bindUserSuccess'));
		}
	}

	private function setUpNewUser(){
		$userName = I('userName');
		$email = I('email');
		$mobile = I('mobile');
		$this->assign('userName', $userName);
		$this->assign('email', $email);
		$this->assign('mobile', $mobile);
		$newPwd = md5(I('newPwd'));
		$newPwdRepeat = md5(I('newPwdRepeat'));
		$emailRegex = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[-_a-z0-9][-_a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})$/i';
		if($userName == ''){
			$this->assign('message', L('userNameEmpty'));
		}else if($email == ''){
			$this->assign('message', L('emailEmpty'));
		}else if(!preg_match($emailRegex,$email)){
			$this->assign('message', L('emailFormatError'));
		}else if(!preg_match('/^\d+$/i',$mobile)){
			$this->assign('message', L('mobileShouldbeNumber'));
		}else if(!preg_match('/^([a-zA-Z0-9@*#]{6,22})$/',I('newPwd'))){
			$this->assign('message', L('newPwdFormatError'));
		}else if($newPwd != $newPwdRepeat){
			$this->assign('message', L('newPwdDifferentWithRepeat'));
		}else{
			$data['userName'] = $userName;
			$data['email'] = $email;
			$data['mobile'] = $mobile;
			$data['password'] = $newPwd;
			$data['userId'] = $this->getCurrentUserId();
			//D('User')->save($data);
			D('User', 'Logic')->updateUserInformation($data, $this->getCurrentUserId());
			session('starballkids_userName', $userName);
			$this->assign('message', L('newUserSetupSuccess'));
		}
	}

	private function changePassword(){
		if(I('currentPwd') == ''){
			$this->assign('message', L('existingPwdEmpty'));
			return;
		}
		if(!preg_match('/^([a-zA-Z0-9@*#]{6,22})$/',I('newPwd'))){
			$this->assign('message', L('newPwdFormatError'));
		}else{
			$currentPwd = md5(I('currentPwd'));
			$newPwd = md5(I('newPwd'));
			$newPwdRepeat = md5(I('newPwdRepeat'));
			$userLogic = D('User', 'Logic');
			$user = $userLogic->getUserInformationByUserId($this->getCurrentUserId());
			if($user['password'] != '' && $user['password'] != $currentPwd){
				$this->assign('message', L('existingPwdIncorrect'));
			}else if($user['password'] != '' && $currentPwd == $newPwd){
				$this->assign('message', L('newPwdSameWithExisting'));
			}else if($newPwd != $newPwdRepeat){
				$this->assign('message', L('newPwdDifferentWithRepeat'));
			}else{
				$data['password'] = $newPwd;
				D('User', 'Logic')->updateUserInformation($data, $this->getCurrentUserId());
				$this->assign('message', L('passwordChanged'));
			}
		}
	}
	
	private function prepareUserInformation(){
		$userLogic = D('User', 'Logic');
		$user = $userLogic->getUserInformationByUserId($this->getCurrentUserId());
		$this->assign('userData', $user);
	}
	
	private function prepareUserAddressInformation(){
		$shippingAddress = D('ShippingAddress', 'Logic');
		$addressList = $shippingAddress->getAllAddress($this->getCurrentUserId());
		$addressList = $this->parseAddressListCode($addressList);
		$this->assign('addressList', $addressList);
	}
	
	private function prepareOrderList(){
		//拿出所有已生成订单号的订单
		$orderLogic = D('Order', 'Logic');
		$map['userId'] = $this->getCurrentUserId();
		$map['orderNumber'] = array('neq','');
		$result = $orderLogic->queryOrder($map);
		$orderStatus = C('ORDERSTATUS');
		$i = 0;
		foreach($result as $record){
			$record['statusDescription'] = $orderStatus[$record['status']];
			//$record['orderDate'] = substr($record['orderDate'], 0,10);
			$result[$i] = $record;
			$i++;
		}
		$this->assign('orderList', $result);
	}

	public function deleteAddress(){
		D('ShippingAddress', 'Logic')->deleteAddress(I('addressId'));
		$this->redirect('User/index');
	}
	
	public function orderinfo($orderId = '', $orderNumber = ''){
		if(!$this->isLogin()){
			$this->redirect('Home/register');
		}
		$this->commonProcess();
		if($orderId != ''){
			$order = D('Order', 'Logic')->findByOrderId($orderId);
		}else if($orderNumber != ''){
			$order = D('Order', 'Logic')->findByOrderNumber($orderNumber);
		}
		$ordeItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($order['orderId']);
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($order['orderNumber']);
		$shippingAddress = D('ShippingAddress', 'Logic')->findExsitingAddress($order['shippingAddress']);
		$orderStatus = C('ORDERSTATUS');
		$order['statusDescription'] = $orderStatus[$order['status']];
		$order['orderDate'] = substr($order['orderDate'], 0,10);
		$this->assign('order', $order);
		$this->assign('orderItems', $ordeItems);
		$this->assign('orderBill', $orderBill);
		$this->assign('address', parseAddressCode($shippingAddress));
		$this->display();
	}
	
	public function confirmDelivery(){
		D('Order', 'Logic')->updateOrderStatus(I('orderId'), 'D', 'V');
		$this->redirect('User/orderinfo', array('orderId' => I('orderId')));
	}
	
	public function cancelOrder(){
		D('Order', 'Logic')->updateOrderStatus(I('orderId'), 'P', 'C1');
		$this->redirect('User/orderinfo', array('orderId' => I('orderId')));
	}
	
	public function refundItem(){
		$startingStatus = I('status');
		$orderItemId = I('orderItemId');
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getOrderItemById($orderItemId);
		if($orderItem['status'] != $startingStatus){
			//Can't refund in non-paid status, can also prevent the resubmit case.
			return;
		}
		
		$data['status'] = 'C1';
		$orderItemLogic->updateOrderItem($data, $orderItemId);
		$this->redirect('User/orderinfo', array('orderId' => $orderItem['orderId']));
	}
	
}
