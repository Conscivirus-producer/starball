<?php
namespace Starball\Controller;
use Think\Controller;
class CartController extends BaseController {
	public function index(){
		$this->testLogShoppingList();
		$this->commonProcess();
		$this->display();
	}
	
	public function test(){
		$this->commonProcess();
		$this->display();
	}
	
	public function submitOrder(){
		$this->commonProcess();
		if(!$this->isLogin()){
			$this->redirect('Home/register', array('fromAction' => 'shoppinglist'));
		}
		$orderLogic = D('Order', 'Logic');
		$userId = $this->getCurrentUserId();
		$backlogOrder = $orderLogic->getOrderByUserId($userId, 'B');
		if(count($backlogOrder) > 0){
			$order = $backlogOrder[0];
			//生成订单号,规则: 数字8(1位) + 年份最后1位，如2016最后一位6(1位) + 月份，如04(2位) + 日期，如12(2位) + 当前秒数,如59(2位) + 用户ID后2位,如87(4位) + 随机数(2位) 
			$strUtil = new \Org\Util\String();
			$orderNumber = '8'.substr(date("Ymds"), 3).substr($userId, -2).$strUtil->randString(2,1);
			$data['orderNumber'] = $orderNumber;
			$data['status'] = 'N';
			$orderLogic->updateOrder($data, $order['orderId']);
			$this->redirect('Payment/index', array('orderNumber' => $orderNumber));
		}
	}
	
}