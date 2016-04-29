<?php
namespace Starball\Controller;
use Think\Controller;
class PaymentController extends BaseController {
	
	public function index(){
		$this->commonProcess();
		$addressInfo = D('ShippingAddress', 'Logic')->getDefaultAddress($this->getCurrentUserId());
		$countryList = C('COUNTRY_LIST');
		$addressInfo['country'] = L($countryList[$addressInfo['country']]);
		$this->assign('addressInfo', $addressInfo);
		$this->assign('orderNumber', I('orderNumber'));
		$this->display();
	}
	
	public function wx(){
		$this->commonProcess();
		//微信支付,需要难登录态,
		$orderNumber = I('orderNumber');
		$orderLogic = D('Order', 'Logic');
		$map['orderNumber'] = $orderNumber;
		$map['status'] = 'N';
		$result = $orderLogic->queryOrder($map);
		if(count($result) == 0){
			echo "该订单不存在或已支付";
			exit();
		}
		$order = $result[0];
		Vendor("beecloud.autoload");
		
		$data = array();
		$appSecret = "b3842787-3442-49eb-914a-5ec86e0b2e74";
		$data["app_id"] = "045c259d-9ceb-4320-84e6-64d463c01a2d";
		$data["timestamp"] = time() * 1000;
		$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
		$data["channel"] = "WX_NATIVE";
		$data["total_fee"] =  intval($order['totalFee'] * 100);
		$data["bill_no"] = $orderNumber.$data["timestamp"];
		//$data["bill_no"] = "bcdemo" . "static";
		$data["title"] = "StarBall.Kids订单".$orderNumber;
		$this->createOrderBill($data, $orderNumber, 'WX', 'PAY');
	    $result = \beecloud\rest\api::bill($data);
	    if ($result->result_code != 0) {
	        print_r($result);
	        exit();
	    }
		//选填 optional
		//$data["return_url"] = "http://starballkids.com/";
		//$data["optional"] = json_decode(json_encode(array("tag"=>"msgtoreturn")));
		$this->assign('codeUrl', $result->code_url);
		$this->assign('totalFee', $data["total_fee"]/100);
		$this->assign('orderNumber', $orderNumber);
		$this->assign('bill_no', $data["bill_no"]);
		$this->assign('is_dev', C('IS_DEV'));
		$this->display();
	}

	public function paysuccess(){
		$this->commonProcess();
		//send mail
		$mailContent = D("Order", "Logic")->getOrderInformationByOrderNumber(I('orderNumber'));
		sendMail($mailContent, "payment");
		$this->assign('orderNumber', I('orderNumber'));
		$this->display();
	}

	private function createOrderBill($data, $orderNumber, $channel, $type){
		$billData['orderNumber'] =  $orderNumber;
		$billData['billNumber'] = $data["bill_no"];
		$billData['title'] = $data["title"];
		$billData['totalAmount'] = $data["total_fee"] / 100;
		$billData['channel'] = $channel;
		$billData['subChannel'] = $data["channel"];
		$billData['type'] = $type;
		$billData['status'] = 'N';
		D('OrderBill', 'Logic')->createBill($billData);
	}
	
	public function wxrefund(){
		//退款
		$this->display();
	}

	public function wxrefunds(){
		//查询退款
		$this->display();
	}
		
	public function query(){
		//查询支付
		Vendor("beecloud.autoload");
		date_default_timezone_set("Asia/Shanghai");
		
		$data = array();
		$data["orderNumber"] = I('orderNumber');
		$data['status'] = 'P';
		$result = D('Order', 'Logic')->queryOrder($data);
		$vo = array();
		if(count($result) == 1){
			$vo['status'] = 1;
			$vo['result_msg'] = 'OK';
		}else{
			$vo['status'] = 0;
			$vo['result_msg'] = 'FAILED';
		}
		//$result = \beecloud\rest\api::bills($data);
		
		print json_encode($vo);
	}
	
	public function testFinishPayment(){
		//更新bill状态
		$billLogic = D('OrderBill', 'Logic');
		$map['billNumber'] = I('bill_no');
		$map['type'] = 'PAY';
		$record = $billLogic->queryBill($map);
		$bill = $record[0];
		$data['status'] = 'S';
		$data['billId'] = $bill['billId'];
		$billLogic->update($data);
		
		//更新订单状态
		$orderData['status'] = 'P';
		D('Order', 'Logic')->updateOrderByNumber($orderData, $bill['orderNumber']);
		d('OrderItem', 'Logic')->updateOrderItemStatusByOrder($bill['orderNumber'], 'P');
		$this->deduceInventoryByOrder($bill['orderNumber']);
		$data = array(
		    'message'=>'处理成功',
		);
		$vo = $data;
		$vo['status'] = 1;
		$this->ajaxReturn($vo, "json");
	}
	
	private function deduceInventoryByOrder($orderNumber){
		$order = D('Order', 'Logic')->findByOrderNumber($orderNumber);
		$orderItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($order['orderId']);
		foreach($orderItems as $record){
			D('Inventory', 'Logic')->updateInventory($record['itemSize'], -$record['quantity']);
		}
	}
	
	public function webhook(){
		header("Content-type: text/html; charset=utf-8");
		$appId = "045c259d-9ceb-4320-84e6-64d463c01a2d";
		$appSecret = "b3842787-3442-49eb-914a-5ec86e0b2e74";
		$jsonStr = file_get_contents("php://input");
		//$jsonStr = file_get_contents(dirname(__FILE__)."/pay_json.txt");
		$msg = json_decode($jsonStr);
		// webhook字段文档: http://beecloud.cn/doc/php.php#webhook
		
		// 验证签名
		$sign = md5($appId . $appSecret . $msg->timestamp);
		if ($sign != $msg->sign) {
		    // 签名不正确
		    exit();
		}
		// 此处需要验证购买的产品与订单金额是否匹配:
		// 验证购买的产品与订单金额是否匹配的目的在于防止黑客反编译了iOS或者Android app的代码，
		// 将本来比如100元的订单金额改成了1分钱，开发者应该识别这种情况，避免误以为用户已经足额支付。
		// Webhook传入的消息里面应该以某种形式包含此次购买的商品信息，比如title或者optional里面的某个参数说明此次购买的产品是一部iPhone手机，
		// 开发者需要在客户服务端去查询自己内部的数据库看看iPhone的金额是否与该Webhook的订单金额一致，仅有一致的情况下，才继续走正常的业务逻辑。
		// 如果发现不一致的情况，排除程序bug外，需要去查明原因，防止不法分子对你的app进行二次打包，对你的客户的利益构成潜在威胁。
		// 如果发现这样的情况，请及时与我们联系，我们会与客户一起与这些不法分子做斗争。而且即使有这样极端的情况发生，
		// 只要按照前述要求做了购买的产品与订单金额的匹配性验证，在你的后端服务器不被入侵的前提下，你就不会有任何经济损失。
		
		if($msg->transactionType == "PAY") {
			
		    //付款信息
		    //支付状态是否变为支付成功
		    $result = $msg->tradeSuccess;
		
		    //messageDetail 参考文档
		    switch ($msg->channelType) {
		        case "WX":
					
					$billLogic = D('OrderBill', 'Logic');
					$map['billNumber'] = $msg->transaction_id;
					$map['type'] = 'PAY';
					$record = $billLogic->queryBill($map);
					if(count($record) == 0){
						logWarn('Payment Webhook:cannot find matched bill record, msg:'.$jsonStr);
						break;						
					}
					$bill = $record[0];
					if($bill['totalAmount'] != $msg->transaction_fee / 100){
						//确认金额确实为业务产生的金额
						logWarn('Payment Webhook:Total Fee not matched, msg:'.$jsonStr);
						break;
					}
					//如果支付成功，则更新状态为SUCCESS,否则为FAILED
					$data['status'] = $result ? 'S' : 'F';
					$data['billId'] = $bill['billId'];
					$billLogic->update($data);
					
					//如果支付成功,更新订单状态为PAID
					if($result){
						$orderData['status'] = 'P';
						D('Order', 'Logic')->updateOrderByNumber($orderData, $bill['orderNumber']);
						//更新库存
						$this->deduceInventoryByOrder($bill['orderNumber']);
					}
		            break;
		        case "ALI":
		            break;
		        case "UN":
		            break;
		    }
		} else if ($msg->transactionType == "REFUND") {
		
		}
		//处理消息成功,不需要持续通知此消息返回success 
		echo 'success';
	}
	
}