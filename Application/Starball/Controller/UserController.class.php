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
				$data['userName'] = I('userName');
				$data['mobile'] = I('mobile');
				$data['userId'] = $this->getCurrentUserId();
				//D('User')->save($data);
				D('User', 'Logic')->updateUserInformation($data, $this->getCurrentUserId());
			}else if(I('method') == 'changePassword'){
				$currentPwd = md5(I('currentPwd'));
				$newPwd = md5(I('newPwd'));
				$newPwdRepeat = md5(I('newPwdRepeat'));

				$userLogic = D('User', 'Logic');
				$user = $userLogic->getUserInformationByUserId($this->getCurrentUserId());
				if($user['password'] == $currentPwd && $newPwd == $newPwdRepeat && $currentPwd != $newPwd){
					$data['password'] = $newPwd;
					D('User', 'Logic')->updateUserInformation($data, $this->getCurrentUserId());
				}				
			}
		}
		$this->prepareUserInformation();
		$this->prepareUserAddressInformation();
		$this->prepareOrderList();
		$this->assign('tab', I('tab'));
		$this->display();
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
			$record['orderDate'] = substr($record['orderDate'], 0,10);
			$result[$i] = $record;
			$i++;
		}
		$this->assign('orderList', $result);
	}

	public function deleteAddress(){
		D('ShippingAddress', 'Logic')->deleteAddress(I('addressId'));
		$this->redirect('User/index');
	}
	
	public function orderinfo($orderId){
		if(!$this->isLogin()){
			$this->redirect('Home/register');
		}
		$this->commonProcess();
		$order = D('Order', 'Logic')->findByOrderId($orderId);
		$ordeItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($orderId);
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($order['orderNumber']);
		$shippingAddress = D('ShippingAddress', 'Logic')->findExsitingAddress($order['shippingAddress']);
		$orderStatus = C('ORDERSTATUS');
		$order['statusDescription'] = $orderStatus[$order['status']];
		$order['orderDate'] = substr($order['orderDate'], 0,10);
		$this->assign('order', $order);
		$this->assign('orderItems', $ordeItems);
		$this->assign('orderBill', $orderBill);
		$this->assign('address', $this->parseAddressCode($shippingAddress));
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
