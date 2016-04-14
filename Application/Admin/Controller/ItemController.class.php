<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/1
 * Time: 下午2:30
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
use Qiniu\Auth;

class ItemController extends Controller {
    public function uploadCsv() {
        $itemLogic = D("Item", "Logic");
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
        $contents = trim(stream_get_contents($handle));
        fclose($handle);
        $res = $itemLogic->bulkUploadUsingCSV($contents);
        echo json_encode($res);
    }

    public function getCategoryInfo() {
        $categoryLogic = D("Category", "Logic");
        echo json_encode($categoryLogic->getAllCategoryInfo());
    }

    public function getBrandInfo() {
        $brandLogic = D("Brand", "Logic");
        echo json_encode($brandLogic->getAllBrandInfo());
    }

    public function upload() {
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $this->assign('qiniuToken',$token);
        $this->display();
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
            "priceCNY",
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

    public function edit() {
        $itemId = I("get.itemId", "");
        if ($itemId == "") {
            die("错误操作");
        }
        $itemLogic = D("Item", "Logic");
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $itemInformation = $itemLogic->getItemInformationById($itemId);
        $this->assign("itemInformation", $itemInformation);
        $this->assign("itemInformationJSON", json_encode($itemInformation));
        $this->assign('qiniuToken',$token);
        $this->display();
    }

    public function test() {
        $imageLogic = D("Image", "Logic");
        echo $imageLogic->deleteOneImageByImageId(24);
    }

    public function updateSingleItem() {
        $fields = array(
            "itemId",
            "name",
            "color",
            "detailDescription",
            "component",
            "brandId",
            "categoryId",
            "grade",
            "gender",
            "priceHKD",
            "priceCNY",
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
        if ($itemLogic->updateOneItem($data) == false) {
            echo json_encode($result);
        } else {
            $result["status"] = "success";
            echo json_encode($result);
        }
    }

    public function deleteImageByQiniuKey() {
        $key = I('get.qiniuKey');
        $imageLogic = D("Image", "Logic");
        $result["status"] = "0";
        if ($imageLogic->deleteImageByQiniuKey($key) == true) {
            $result["status"] = "1";
        }
        echo json_encode($result);
    }

    public function deleteImageByImageId() {
        $imageId = I('get.imageDeleteId');
        $imageLogic = D("Image", "Logic");
        $result["status"] = "0";
        if ($imageLogic->deleteOneImageByImageId($imageId) == true) {
            $result["status"] = "1";
        }
        echo json_encode($result);
    }

    public function search() {
        $itemLogic = D("Item", "Logic");
        $fields = array(
            "name",
            "color",
            "detailDescription",
            "component",
            "brandId",
            "categoryId",
            "grade",
            "gender",
            "priceHKDL",
            "priceHKDH",
            "priceCNYL",
            "priceCNYH",
            "season"
        );
        $conditions = array();
        $selectConditions = array(
            "brandId",
            "categoryId",
            "grade",
            "gender"
        );
        for ($i = 0; $i < count($fields); $i++) {
            if (in_array($fields[$i], $selectConditions)) {
                $conditions[$fields[$i]] = trim(I("post.".$fields[$i], "noting"));
            } else {
                $conditions[$fields[$i]] = trim(I("post.".$fields[$i], ""));
            }
        }
        $this->assign("conditions", $conditions);
        $this->assign("conditionsJSON", json_encode($conditions));
        $this->assign("searchResult", $itemLogic->search($conditions));
        $this->display();
    }

    function deleteOneItemByItemId() {
        $itemLogic = D("Item", "Logic");
        $itemId = I("get.deleteItemId", "");
        $res["status"] = "0";
        if ($itemLogic->deleteOneItemByItemId($itemId) == true) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
}