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
	
	public function orderinfo($orderId){
		if(IS_POST){
			if(I('method') == 'returnFund'){
				$this->returnFund();
			}
			if(I('method') == 'returnGood'){
				$this->returnGood();
			}
		}
		$this->commonProcess();
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
	
	private function returnFund(){
		$orderItemId = I('orderItemId');
		$orderItemLogic = D('OrderItem', 'Logic');
		$orderItem = $orderItemLogic->getOrderItemById($orderItemId);
		
		$data['status'] = 'C1';
		$orderItemLogic->updateOrderItem($data, $orderItemId);
		logInfo('$orderItemId:'.$orderItemId);
	}
	
	private function returnGood(){
		$orderItemId = I('orderItemId');
		$orderItem = D('OrderItem', 'Logic')->getOrderItemById($orderItemId);
		
		$orderCancelLogic = D('OrderCancel', 'Logic');
		$data['userId'] = $this->getCurrentUserId();
		$data['orderNumber'] = '';//生成退单号
		$data['orderItemId'] = $orderItemId;
		$data['quantity'] = $orderItem['quantity'];//之后要改成可以让用户输入数量，小于等于所有数量
		$data['amount'] = round(($orderItem['price']/$orderItem['quantity']) * $data['quantity'], 2);
		$data['status'] = 'N';
		$orderLogic->create($data);
		$cancelId = $orderLogic->add();
		
		echo $orderItemId;
	}
}
