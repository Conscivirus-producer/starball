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
            "isGiftPackage",
            "userName",
            "mobile",
            "email"
        );
        $map = array();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i] == "status" || $fields[$i] == "isGiftPackage") {
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
}