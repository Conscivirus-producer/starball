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
		$inadequateInventoryItems = $this->checkOrderItemsInventory($order['orderId']);
		if(count($inadequateInventoryItems) > 0){
			session('quantityCheckResult', 'addedItemsNoEnoughInventory');
			$this->redirect('Cart/index');
		}
		Vendor("beecloud.autoload");
		
		$data = array();
		$appSecret = C('APP_SECRET');
		$data["app_id"] = C('APP_ID');
		$data["timestamp"] = time() * 1000;
		$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
		$data["channel"] = "WX_NATIVE";
		$data["total_fee"] =  intval($order['totalFee'] * 100);
		//商户订单号, 8到32位数字和/或字母组合，请自行确保在商户系统中唯一，同一订单号不可重复提交，否则会造成订单重复.
		//这里直接在订单号后面加上时间戳作为付款的订单号
		$data["bill_no"] = $orderNumber.$data["timestamp"];
		if(C('IS_DEV') == 'true'){
			$data["title"] = "Testing开发测试订单".$orderNumber;	
		}else{
			$data["title"] = "StarBall.Kids订单".$orderNumber;
		}
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
		$billData['totalAmount'] = $data["total_fee"] / 100;
		$billData['title'] = $data["title"];
		$billData['subChannel'] = $data["channel"];
		$billData['channel'] = $channel;
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
        $res = array(
            "status" => "0"
        );
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
		$orderLogic = D('Order', 'Logic');
        if ($orderLogic->updateOrderStatus($orderId, 'N', 'P') !== false) {
            $res["status"] = "1";
        }
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->findByOrderNumber($bill['orderNumber']);
		
        if ($orderLogic->updateOrderStatus($order['orderId'], 'N', 'P') !== false) {
			$this->deduceInventoryByOrder($bill['orderNumber']);
            $res["status"] = "1";
        }
		
		echo json_encode($res);
	}
	
	public function testFinishRefundOrder(){
        $res = array(
            "status" => "0"
        );
		$orderId = I('orderId');
		$orderLogic = D('Order', 'Logic');
		$order = $orderLogic->findByOrderId($orderId);
		$map['type'] = 'REFUND';
		$map['orderItemId'] = 0;
		$map['orderNumber'] = $order['orderNumber'];
		$billLogic = D('OrderBill', 'Logic');
		$record = $billLogic->queryBill($map);
		$bill = $record[0];
		
		//更新bill状态
		$data['status'] = 'S';
		$data['billId'] = $bill['billId'];
		$billLogic->update($data);
		
		//更新库存,在更新状态之前操作
		$this->increaseInventoryByOrder($orderId);
		
		//更新order状态
		$orderLogic->updateOrderStatus($orderId, 'C2', 'C3');
		
		
		$res["status"] = "1";
		echo json_encode($res);
	}

	public function testFinishRefundSingleItem(){
        $res = array(
            "status" => "0"
        );
		
		$orderItemId = I('cancelId');
		$billLogic = D('OrderBill', 'Logic');
		$map['orderItemId'] = $orderItemId;
		$map['type'] = 'REFUND';
		$record = $billLogic->queryBill($map);
		$bill = $record[0];
		
		//更新bill状态
		$data['status'] = 'S';
		$data['billId'] = $bill['billId'];
		$billLogic->update($data);
		
		//更新库存,在更新状态之前操作
		$this->increaseInventoryByOrderItem($orderItemId);
		
		//更新orderitem状态
		$orderItemData['status'] = 'C3';
		D('OrderItem', 'Logic')->updateOrderItem($orderItemData, $orderItemId);
		
		
		$res["status"] = "1";
		echo json_encode($res);
	}
	
	private function increaseInventoryByOrderItem($orderItemId){
		//更新库存,之前预留的库存释放
		$orderItem = D('OrderItem', 'Logic')->getOrderItemById($orderItemId);
		D('Inventory', 'Logic')->updateInventory($orderItem['itemSize'], $orderItem['quantity']);
	}
	
	private function increaseInventoryByOrder($orderId){
		$orderItems = D('OrderItem', 'Logic')->getOrderItemsByOrdeId($orderId);
		foreach($orderItems as $orderItem){
			//处于等待退款状态下的商品库存才变回来
			if($orderItem['status'] == 'C2'){
				D('Inventory', 'Logic')->updateInventory($orderItem['itemSize'], $orderItem['quantity']);
			}
		}
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
		$appId = C('APP_ID');
		$appSecret = C('APP_SECRET');
		$jsonStr = file_get_contents("php://input");
		logInfo('ReturnJson:'.$jsonStr);
		//$jsonStr = file_get_contents(dirname(__FILE__)."/refund_json111.txt");
		$msg = json_decode($jsonStr);
		// webhook字段文档: http://beecloud.cn/doc/php.php#webhook
		
		// 验证签名
		$sign = md5($appId . $appSecret . $msg->timestamp);
		if ($sign != $msg->sign) {
		    // 签名不正确
		    logWarn('Signature incorrect.');
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
					$map['billNumber'] = $msg->transactionId;
					$map['type'] = 'PAY';
					$record = $billLogic->queryBill($map);
					if(count($record) == 0){
						logWarn('Payment Webhook:cannot find matched bill pay record.');
						echo 'success';
						return;						
					}
					$bill = $record[0];
					if($bill['totalAmount'] != $msg->transactionFee / 100){
						//确认金额确实为业务产生的金额
						logWarn('Payment Webhook:Pay total Fee not matched.');
						echo 'success';
						return;
					}
					//如果支付成功，则更新状态为SUCCESS,否则为FAILED
					$data['status'] = $result ? 'S' : 'F';
					$data['billId'] = $bill['billId'];
					$billLogic->update($data);
					
					//如果支付成功,更新订单状态为PAID
					if($result){
						$orderLogic = D('Order', 'Logic');
						$order = $orderLogic->findByOrderNumber($bill['orderNumber']);
				        $orderLogic->updateOrderStatus($order['orderId'], 'N', 'P');
						
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
		    //付款信息
		    //支付状态是否变为支付成功
		    $result = $msg->tradeSuccess;
			$map['refundNumber'] = $msg->transactionId;
			$map['type'] = 'REFUND';
			$billLogic = D('OrderBill', 'Logic');
			$record = $billLogic->queryBill($map);
			if(count($record) == 0){
				logWarn('Payment Webhook:cannot find matched bill refund record.');
				echo 'success';
				return;		
			}
			$bill = $record[0];
			if($bill['totalAmount'] != $msg->transactionFee / 100){
				//确认金额确实为业务产生的金额
				logWarn('Payment Webhook:Refund total Fee not matched.');
				echo 'success';
				return;
			}
			
			//如果支付成功，则更新状态为SUCCESS,否则为FAILED
			$data['status'] = $result ? 'S' : 'F';
			$data['billId'] = $bill['billId'];
			$billLogic->update($data);
			
			if($bill['orderItemId'] == 0){
				if($result){
					//Entire order refund
					$orderLogic = D('Order', 'Logic');
					$order = $orderLogic->findByOrderNumber($bill['orderNumber']);
					//update inventory
					$this->increaseInventoryByOrder($order['orderId']);
			        if ($orderLogic->updateOrderStatus($order['orderId'], 'C2', 'C3') == false) {
			            logWarn('Payment Webhook:Order/Order item status not match.');
						return;
			        }
				}
			}else{
				//Single order item refund
				//如果退款成功,更新orderitem状态
				$orderItem = D('OrderItem', 'Logic')->getOrderItemById($bill['orderItemId']);
				if($orderItem['status'] != 'C2'){
					logWarn('Payment Webhook:Order Item status not C2, return.');
					return;
				}
				if($result){
					$orderItemData['status'] = 'C3';
					D('OrderItem', 'Logic')->updateOrderItem($orderItemData, $bill['orderItemId']);
					//更新库存
					$this->increaseInventoryByOrderItem($bill['orderItemId']);
				}
			}
		}
		//处理消息成功,不需要持续通知此消息返回success 
		echo 'success';
	}
	
}