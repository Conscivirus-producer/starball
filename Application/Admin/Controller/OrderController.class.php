<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/17
 * Time: 下午4:21
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
use Qiniu\Auth;

class OrderController extends Controller {
    public function index() {
        $fields = array(
            "createdDateStart",
            "createdDateEnd",
            "status",
            "userName",
            "mobile",
            "email"
        );
        $map = array();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i] == "status") {
                $map[$fields[$i]] = trim(I("post.".$fields[$i], "nothing"));
            } else {
                $map[$fields[$i]] = trim(I("post.".$fields[$i], ""));
            }
        }
        $orderLogic = D("Order", "Logic");
        $this->assign("orders", $orderLogic->getOrderInformationWithUserInformation($map));
        $this->assign("conditions", $map);
        $this->assign("conditionsJSON", json_encode($map));
        $this->display();
    }

    public function showDetailedInformation() {
        $orderLogic = D("Order", "Logic");
        $orderId = trim(I("get.orderId", ""));
        if ($orderId == "") {
            die("错误操作");
        }
		$this->assign('is_dev', C('IS_DEV'));
        $this->assign("information", $orderLogic->getOrderInformationByOrderId($orderId));
        $this->display();
    }

    public function confirmDelivery() {
        $orderLogic = D("Order", "Logic");
        $res = array(
            "status" => "0"
        );
        $fields = array(
            "expressName",
            "expressNumber",
            "orderNumber",
            "email",
            "userName",
            "orderId"
        );
        $data = array();
        for($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i], "");
            if ($data[$fields[$i]] == "") {
                echo json_encode($res);
                return;
            }
        }
        if ($orderLogic->confirmDelivery($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function confirmReceive() {
        $orderLogic = D("Order", "Logic");
        $res = array(
            "status" => "0"
        );
        $orderId = I("post.orderId", "");
        if ($orderId == "") {
            echo json_encode($res);
            return;
        }
        if ($orderLogic->confirmReceive($orderId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function cancelEntireOrder() {
        $orderLogic = D("Order", "Logic");
        $res = array(
            "status" => "0"
        );
        $orderId = I("post.orderId", "");
        if ($orderId == "") {
            echo json_encode($res);
            return;
        }
		
		//找到支付成功的bill记录
		$order = D('Order', 'Logic')->findByOrderId($orderId);
		$orderNumber = $order['orderNumber'];
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($orderNumber);
		
		//向第三方支付发起退款请求
		$data = array();
		$appSecret = C('PAYMENT_APP_SECRET');
		$data["app_id"] = C('PAYMENT_APP_ID');
		$data["timestamp"] = time() * 1000;
		$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
		//bill_no为支付成功的支付单号
		$data["bill_no"] = $orderBill['billNumber'];
		//商户退款单号,格式为:退款日期(8位) + 流水号(3~24 位)。请自行确保在商户系统中唯一，且退款日期必须是发起退款的当天日期,同一退款单号不可重复提交，否则会造成退款单重复。流水号可以接受数字或英文字符，建议使用数字，但不可接受“000”
		$data["refund_no"] = date("Ymd").$data["timestamp"];
		//退款费用要去除之前已经单个申请退款的商品. 未退款的商品+运费+
		$excludedFee = D('OrderItem', 'Logic')->calculateExcludedItemsFee($orderId);
		$data["refund_fee"] =  intval(($order['totalFee'] - $excludedFee) * 100);
		//选择渠道类型(WX、WX_APP、WX_NATIVE、WX_JSAPI、ALI、ALI_APP、ALI_WEB、ALI_QRCODE、UN、UN_APP、UN_WEB)
		$data["channel"] = $orderBill['channel'];
		//选填 optional
		$data["optional"] = json_decode(json_encode(array("tag"=>"msgtoreturn")));
		
		//创建退款的数据库记录,t_orderbill
		$billData['orderNumber'] =  $orderNumber;
		$billData['billNumber'] = $data["bill_no"];
		$billData['refundNumber'] = $data["refund_no"];
		$billData['totalAmount'] = $data["refund_fee"] / 100;
		$billData['channel'] = $data["channel"];
		$billData['type'] = 'REFUND';
		$billData['status'] = 'N';
		D('OrderBill', 'Logic')->createBill($billData);
		//$this->createOrderBill($data, $orderNumber, $data["channel"], 'REFUND');
		
		if(C('IS_DEV') == 'false'){
			//本地测试不用向第三方发送请求
			Vendor("beecloud.autoload");
		    $result = \beecloud\rest\api::refund($data);
		    if ($result->result_code != 0 || $result->result_msg != "OK") {
		        echo json_encode($result->err_detail);
				logInfo('errorDetail:'.$result->err_detail);
		        exit();
		    }
		}
		
        if ($orderLogic->updateOrderStatus($orderId, 'C1', 'C2') !== false) {
            $res["status"] = "1";
        }
		if($result->url != ''){
			$res["url"] = $result->url;	
		}
        echo json_encode($res);
    }
	
	//退款单个item
    public function cancelSingleOrderItem() {
        $orderItemLogic = D("OrderItem", "Logic");
        $res = array(
            "status" => "0"
        );
        $id = I("post.cancelId", "");
        if ($id == "") {
            echo json_encode($res);
            return;
        }
		
		//找到支付成功的bill记录
		$orderItem = D('OrderItem', 'Logic')->getOrderItemById($id);
		$order = D('Order', 'Logic')->findByOrderId($orderItem['orderId']);
		$orderNumber = $order['orderNumber'];
		$orderBill = D('OrderBill', 'Logic')->findOrderSuccessPayBill($orderNumber);
		
		//向第三方支付发起退款请求
		$data = array();
		$appSecret = C('PAYMENT_APP_SECRET');
		$data["app_id"] = C('PAYMENT_APP_ID');
		$data["timestamp"] = time() * 1000;
		$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
		//bill_no为支付成功的支付单号
		$data["bill_no"] = $orderBill['billNumber'];
		//商户退款单号,格式为:退款日期(8位) + 流水号(3~24 位)。请自行确保在商户系统中唯一，且退款日期必须是发起退款的当天日期,同一退款单号不可重复提交，否则会造成退款单重复。流水号可以接受数字或英文字符，建议使用数字，但不可接受“000”
		$data["refund_no"] = date("Ymd").$data["timestamp"];
		$data["refund_fee"] = intval($orderItem['price'] * 100);
		//选择渠道类型(WX、WX_APP、WX_NATIVE、WX_JSAPI、ALI、ALI_APP、ALI_WEB、ALI_QRCODE、UN、UN_APP、UN_WEB)
		$data["channel"] = $orderBill['channel'];
		//选填 optional
		$data["optional"] = json_decode(json_encode(array("tag"=>"msgtoreturn")));
		
		//创建退款的数据库记录,t_orderbill
		$billData['orderNumber'] =  $orderNumber;
		$billData['billNumber'] = $data["bill_no"];
		$billData['refundNumber'] = $data["refund_no"];
		//只有退单个商品时才有值 
		$billData['orderItemId'] =  $orderItem['id'];
		$billData['totalAmount'] = $data["refund_fee"] / 100;
		$billData['channel'] = $data["channel"];
		$billData['type'] = 'REFUND';
		$billData['status'] = 'N';
		D('OrderBill', 'Logic')->createBill($billData);
		//$this->createOrderBill($data, $orderNumber, $data["channel"], 'REFUND');
		
		if(C('IS_DEV') == 'false'){
			//本地测试不用向第三方发送请求
			Vendor("beecloud.autoload");
		    $result = \beecloud\rest\api::refund($data);
		    if ($result->result_code != 0 || $result->result_msg != "OK") {
		        echo json_encode($result->err_detail);
				logInfo('errorDetail:'.$result->err_detail);
		        exit();
		    }
		}
		D('OrderItem', 'Logic')->cancelSingleOrderItem($id);
		$res["status"] = "1";
		logInfo('url:'.$result->url);
		if($result->url != ''){
			$res["url"] = $result->url;	
		}
        echo json_encode($res);
    }

    public function cancel() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $fields = array(
            "status",
            "createdDateStart",
            "createdDateEnd"
        );
        $conditions = array();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i] == "status") {
                $conditions[$fields[$i]] = I("post.".$fields[$i], "nothing");
            } else {
                $conditions[$fields[$i]] = I("post.".$fields[$i], "");
            }
        }
        $orderCancelInformation = $orderCancelLogic->getCancelOrdersByConditions($conditions);
        $this->assign("data", $orderCancelInformation);
        $this->assign("conditions", $conditions);
        $this->assign("conditionsJSON", json_encode($conditions));
        $this->display();
    }

    public function cancelDetailedInformation() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $cancelId = I("get.cancelId", "");
        if ($cancelId == "") {
            die("错误操作");
        }
        $this->assign("information", $orderCancelLogic->getCancelOrderDetailedInformation($cancelId));
        $this->display();
    }

    public function agreeCancelOrder() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $res = array(
            "status" => "0"
        );
        $cancelId = I("post.cancelId", "");
        if ($orderCancelLogic->agreeCancelOrder($cancelId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function disagreeCancelOrder() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $res = array(
            "status" => "0"
        );
        $cancelId = I("post.cancelId", "");
        if ($orderCancelLogic->disagreeCancelOrder($cancelId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function cancelOrderVerifyReceive() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $res = array(
            "status" => "0"
        );
        $cancelId = I("post.cancelId", "");
        if ($orderCancelLogic->cancelOrderVerifyReceive($cancelId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function cancelOrderRefund() {
        $orderCancelLogic = D("OrderCancel", "Logic");
        $res = array(
            "status" => "0"
        );
        $cancelId = I("post.cancelId", "");
        if ($orderCancelLogic->cancelOrderRefund($cancelId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
}