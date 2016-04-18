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
        $orderLogic = D("Order", "Logic");
        $map = array();
        $this->assign("orders", $orderLogic->getOrderInformationWithUserInformation($map));
        $this->display();
    }
}