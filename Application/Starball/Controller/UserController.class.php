<?php
namespace Starball\Controller;
use Think\Controller;
class UserController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => I('tab')));
		}
		$this->prepareOrderList();
		$this->assign('tab', I('tab'));
		$this->display();
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
	
	public function orderinfo(){
		$this->commonProcess();
		$orderId = I('orderId');
		$order = D('Order', 'Logic')->findByOrderId($orderId);
		$ordeItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($orderId);
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($orderId);
		$orderStatus = C('ORDERSTATUS');
		$order['statusDescription'] = $orderStatus[$order['status']];
		$order['orderDate'] = substr($order['orderDate'], 0,10);
		$this->assign('order', $order);
		$this->assign('orderItems', $ordeItems);
		$this->assign('orderBill', $orderBill);
		$this->display();
	}
}
