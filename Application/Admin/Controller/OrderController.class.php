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
        if ($orderLogic->cancelEntireOrder($orderId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

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
        if ($orderItemLogic->cancelSingleOrderItem($id) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
}