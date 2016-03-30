<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
use Qiniu\Auth;

class HomeController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function uploadCsv() {
        $filename = $_FILES['file']['tmp_name'];
        $callback = array(
            "status" => "",
            "number" => ""
        );

        if (empty ($filename)) {
            $callback["status"] = "0";
            echo json_encode($callback);
            exit;
        }
        $handle = fopen($filename, 'r');
        $contents = stream_get_contents($handle);
        fclose($handle);
        echo split("\n", $contents)[0];
    }

    public function getCategoryInfo() {
        $categoryLogic = D("Category", "Logic");
        echo json_encode($categoryLogic->getAllCategoryInfo());
    }

    public function getBrandInfo() {
        $brandLogic = D("Brand", "Logic");
        echo json_encode($brandLogic->getAllBrandInfo());
    }

    public function getQiniuToken() {
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $result = array(
            "token" => $token
        );
        echo json_encode($result);
    }

    public function uploadSingleItem() {
        $fields = array(
            "name",
            "color",
            "detailDescription",
            "component",
            "brandId",
            "categoryId",
            "grade",
            "gender",
            "priceHKD",
            "season",
            "images_array"
        );
        $result = array(
            "status" => "fail"
        );
        $data = array();
        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $data[$fields[$i]] = I('post.'.$fields[$i]);
        }
        $itemLogic = D("Item", "Logic");
        if ($itemLogic->insertOneItem($data) == false) {
            echo json_encode($result);
        } else {
            $result["status"] = "success";
            echo json_encode($result);
        }
    }
}