<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/5/30
 * Time: 下午5:59
 */
namespace Admin\Controller;
use Think\Controller;
use Qiniu\Auth;
class DataController extends Controller {
    public function index() {
        $supportingDataLogic = D("SupportingData", "Logic");
        $this->assign("data", $supportingDataLogic->getAllKeyAndValues());
        $this->display();
    }

    public function create() {
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $this->assign('qiniuToken',$token);
        $this->display();
    }

    public function checkKeyExistence() {
        $res = array(
            "status" => "0"
        );
        $key = I("get.key", "");
        if ($key == "") {
            echo json_encode($res);
            return;
        }
        $supportingDataLogic = D("SupportingData", "Logic");
        if ($supportingDataLogic->checkKeyExistence($key) === true) {
            $res["status"] = "1";
            echo json_encode($res);
            return;
        }
        echo json_encode($res);
    }

    public function insertOneKeyAndValue() {
        $supportingDataLogic = D("SupportingData", "Logic");
        $res = array(
            "status" => "0"
        );
        $fields = array(
            "key",
            "value",
            "type",
            "remark"
        );
        $data = array();
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($supportingDataLogic->insertOneKeyAndValue($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function edit() {
        $supportingDataLogic = D("SupportingData", "Logic");
        $key = I("get.key", "");
        if ($key == "") {
            die("操作错误!");
        }
        $this->assign("data", $supportingDataLogic->getAllInformationByKey($key));
        $this->assign("dataJSON", json_encode($supportingDataLogic->getAllInformationByKey($key)));
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $this->assign('qiniuToken',$token);
        $this->display();
    }

    public function updateOneKeyAndValue() {
        $supportingDataLogic = D("SupportingData", "Logic");
        $res = array(
            "status" => "0"
        );
        $fields = array(
            "key",
            "value",
            "remark"
        );
        $data = array();
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($supportingDataLogic->updateOneKeyAndValue($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function sendMail() {
        $user["email"] = "1415609649@qq.com";
        $user["userName"] = "dingjunnan";
        $userInfo = array();
        array_push($userInfo, $user);
        $user["email"] = "acrushdjn@163.com";
        $user["userName"] = "djn";
        array_push($userInfo, $user);
        sendMailNewVersion("", "notifyMyself", $userInfo);
    }
}


















