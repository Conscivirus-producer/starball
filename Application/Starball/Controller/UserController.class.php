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
				logInfo('fk111111');
				logInfo('existingpwd:'.$user['password']);
				logInfo('newPwd:'.$newPwd);
				if($user['password'] == $currentPwd && $newPwd == $newPwdRepeat && $currentPwd != $newPwd){
					logInfo('fk222');
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
	
	public function orderinfo($orderId){
		if(!$this->isLogin()){
			$this->redirect('Home/register');
		}
		if(IS_POST){
			if(I('method') == 'cancelOrder'){
				$this->cancelOrder();
			}
			if(I('method') == 'returnFund'){
				$this->refundItem('P');
			}
			if(I('method') == 'returnGood'){
				$this->refundItem('V');
			}
			if(I('method') == 'confirmDelivery'){
				$this->confirmDelivery();
			}
		}
		$this->commonProcess();
		$order = D('Order', 'Logic')->findByOrderId($orderId);
		$ordeItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($orderId);
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($order['orderNumber']);
		$orderStatus = C('ORDERSTATUS');
		$order['statusDescription'] = $orderStatus[$order['status']];
		$order['orderDate'] = substr($order['orderDate'], 0,10);
		$this->assign('order', $order);
		$this->assign('orderItems', $ordeItems);
		$this->assign('orderBill', $orderBill);
		$this->display();
	}
	
	private function confirmDelivery(){
		D('Order', 'Logic')->updateOrderStatus(I('orderId'), 'D', 'V');
	}
	
	private function cancelOrder(){
		D('Order', 'Logic')->updateOrderStatus(I('orderId'), 'P', 'C1');
	}
	
	private function refundItem($startingStatus){
		$orderItemId = I('orderItemId');
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getOrderItemById($orderItemId);
		if($orderItem['status'] != $startingStatus){
			//Can't refund in non-paid status, can also prevent the resubmit case.
			return;
		}
		
		$data['status'] = 'C1';
		$orderItemLogic->updateOrderItem($data, $orderItemId);
	}
	
	//No-used function
	private function returnGood(){
		/*$orderCancelLogic = D('OrderCancel', 'Logic');
		$data['userId'] = $this->getCurrentUserId();
		$data['orderNumber'] = '';//生成退单号
		$data['orderItemId'] = $orderItemId;
		$data['quantity'] = $orderItem['quantity'];//之后要改成可以让用户输入数量，小于等于所有数量
		$data['amount'] = round(($orderItem['price']/$orderItem['quantity']) * $data['quantity'], 2);
		$data['status'] = 'N';
		$orderLogic->create($data);
		$cancelId = $orderLogic->add();*/
		
	}
}
