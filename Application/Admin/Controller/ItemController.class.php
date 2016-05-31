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
        $allItemSize = C("ITEMSIZE");
        $this->assign("itemSizeData", json_encode($allItemSize));
        $this->assign('qiniuToken',$token);
		$supportingData = D('SupportingData', 'Logic');
		$this->assign('defaultClothWeight', $supportingData->getValueByKey('SHIPPING_COMMON_CLOTH_WEIGHT'));
        $this->display();
    }
	
	private function extendNumber($inventorySizeStart,$inventorySizeEnd){
		$result = '';
		for($i = 0;$inventorySizeStart + $i <= $inventorySizeEnd;$i++){
			$result = $result.($inventorySizeStart + $i).',';
		}
		return $result;
	}

    public function uploadSingleItem() {
        $result = array(
            "status" => "fail"
        );
        // inventory process
        $productType = I("post.product-type", "");
        if ($productType == "") {
            echo json_encode($result);
            return;
        }
        $inventoryCount = (int)(I("post.inventory-div-count"));
        $inventoryArray = array();
        $inventory = array();
        for ($i = 0; $i <= $inventoryCount; $i++) {
            $shoeSize = I("post.shoeSize".$i, "");
            $inventorySizeStart = I("post.inventory-size-start".$i, "");
            $inventorySizeEnd = I("post.inventory-size-end".$i, "");
            $inventoryNumber = I("post.inventory-number".$i, "");
            $inventoryPriceCNY = I("post.inventory-price-CNY".$i, "");
            $inventoryPriceHKD = I("post.inventory-price-HKD".$i, "");
            if ($inventorySizeStart == "" || $inventorySizeEnd == "" || $inventoryNumber == "" || $inventoryPriceCNY == "" || $inventoryPriceHKD == "") {
                continue;
            }
            if ($productType == "2" && $shoeSize == "") {
                continue;
            }
            $inventory["productType"] = $productType;
            $inventory["footSize"] = $shoeSize;
            if ($inventory["productType"] != "2") {
                $inventory["age"] = $this->extendNumber($inventorySizeStart, $inventorySizeEnd);
            } else{
                $inventory["age"] = "";
            }
            $inventory["inventory"] = $inventoryNumber;
            $inventory["priceCNY"] = $inventoryPriceCNY;
            $inventory["priceHKD"] = $inventoryPriceHKD;
            array_push($inventoryArray, $inventory);
        }
        $fields = array(
            "name",
            "color",
            "detailDescription",
            "component",
            "brandId",
            "categoryId",
            "grade",
            "gender",
            "season",
            "weight",
            "extraShippingFee",
            "isAvailable",
            "discount",
            "tag",
            "images_array"
        );
        $data = array();
        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $data[$fields[$i]] = I('post.'.$fields[$i], "");
            // validation in the server side, ignore tag, tag is not necessary
            if ($fields[$i] != "tag") {
                if ($data[$fields[$i]] == "") {
                    echo json_encode($result);
                    // stop if meets empty value
                    return;
                }
            }
        }
        $data["inventory"] = $inventoryArray;
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
        $allItemSize = C("ITEMSIZE");
        $this->assign("itemSizeData", json_encode($allItemSize));
        $this->assign("itemInformation", $itemInformation);
        $this->assign("itemInformationJSON", json_encode($itemInformation));
        $this->assign('qiniuToken',$token);
		$subscriptionList = D('ItemSubscription', 'Logic')->queryByItemId($itemId);
		$this->assign('subscriptionList', $subscriptionList);
        $this->display();
    }

    public function test() {
        $orderLogic = D("Order", "Logic");
        print_r($orderLogic->getOrderInformationByOrderId(30));
    }

    public function updateSingleItem() {
        $result = array(
            "status" => "fail"
        );
        // inventory process
        $productType = I("post.product-type", "");
        if ($productType == "") {
            echo json_encode($result);
            return;
        }
        $inventoryCount = (int)(I("post.inventory-div-count"));
        $inventoryArray = array();
        $inventory = array();
        for ($i = 0; $i <= $inventoryCount; $i++) {
            $shoeSize = I("post.shoeSize".$i, "");
            $inventoryId = I("post.inventory-id".$i, "");
            $inventorySizeStart = I("post.inventory-size-start".$i, "");
            $inventorySizeEnd = I("post.inventory-size-end".$i, "");
            $inventoryNumber = I("post.inventory-number".$i, "");
            $inventoryPriceCNY = I("post.inventory-price-CNY".$i, "");
            $inventoryPriceHKD = I("post.inventory-price-HKD".$i, "");
            if ($inventorySizeStart == "" || $inventorySizeEnd == "" || $inventoryNumber == "" || $inventoryPriceCNY == "" || $inventoryPriceHKD == "") {
                continue;
            }
            if ($productType == "2" && $shoeSize == "") {
                continue;
            }
            $inventory["productType"] = $productType;
            $inventory["footSize"] = $shoeSize;
            $inventory["inventoryId"] = $inventoryId;
            if ($inventory["productType"] != "2") {
                $inventory["age"] = $this->extendNumber($inventorySizeStart, $inventorySizeEnd);
            } else{
                $inventory["age"] = "";
            }
            $inventory["inventory"] = $inventoryNumber;
            $inventory["priceCNY"] = $inventoryPriceCNY;
            $inventory["priceHKD"] = $inventoryPriceHKD;
            array_push($inventoryArray, $inventory);
        }
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
            "season",
            "weight",
            "extraShippingFee",
            "isAvailable",
            "discount",
            "tag",
            "images_array"
        );
        $data = array();
        $data["inventory"] = $inventoryArray;
        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $data[$fields[$i]] = I('post.'.$fields[$i]);
            // validation in the server side, ignore tag, tag is not necessary
            if ($fields[$i] != "tag") {
                if ($data[$fields[$i]] == "") {
                    echo json_encode($result);
                }
            }
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
                $conditions[$fields[$i]] = trim(I("post.".$fields[$i], "nothing"));
            } else {
                $conditions[$fields[$i]] = trim(I("post.".$fields[$i], ""));
            }
        }
        $this->assign("conditions", $conditions);
        $this->assign("conditionsJSON", json_encode($conditions));
        $this->assign("searchResult", $itemLogic->search($conditions));
        $this->display();
    }

    public function deleteOneItemByItemId() {
        $itemLogic = D("Item", "Logic");
        $itemId = I("get.deleteItemId", "");
        $res["status"] = "0";
        if ($itemLogic->deleteOneItemByItemId($itemId) == true) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function mainPageSetting() {
        $data = array();
        $hotItemLogic = D("Hotitem", "Logic");
        $categories = array("H", "MLH", "MLF", "MR", "F", "S");
        for($i = 0; $i < count($categories); $i++) {
            $data[$categories[$i]] = $hotItemLogic->getHotItems($categories[$i]);
        }
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $this->assign('qiniuToken',$token);
        $this->assign("data", $data);
        $this->display();
    }

    public function addOneMainPageSetting() {
        $res = array(
            "status" => "0"
        );
        $hotItemLogic = D("Hotitem", "Logic");
        $fields = array(
            "title",
            "subtitle",
            "targetItemLink",
            "additionalLink",
            "active",
            "type",
            "image",
        );
        $data = array();
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($hotItemLogic->insertOneHotItem($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function editMainPageSetting() {
        $hotItemLogic = D("Hotitem", "Logic");
        $hotId = I("get.hotId", "");
        if ($hotId == "") {
            die("错误操作");
        }
        vendor("qiniusdk.autoload");
        $accessKey = 'k7HBysPt-HoUz4dwPT6SZpjyiuTdgmiWQE-7qkJ4';
        $secretKey = 'BuaBzxTxNsNUBSy1ZvFUAfUbj8GommyWbfJ0eQ2R';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'image';
        $token = $auth->uploadToken($bucket,null,3600,null,true);
        $this->assign('qiniuToken', $token);
        $this->assign('data', $hotItemLogic->getHotItemInformationById($hotId));
        $this->assign('dataJSON', json_encode($hotItemLogic->getHotItemInformationById($hotId)));
        $this->display();
    }

    public function updateMainPageSetting() {
        $res = array(
            "status" => "0"
        );
        $hotItemLogic = D("Hotitem", "Logic");
        $fields = array(
            "hotId",
            "title",
            "subtitle",
            "targetItemLink",
            "additionalLink",
            "active",
            "image",
            "imageChanged"
        );
        $data = array();
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($hotItemLogic->updateOneHotItem($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
	
	public function changeCategory(){
        $res = array(
            "itemWeight" => "0"
        );
		$categoryId = I('categoryId');
		$data = D('Category', 'Logic')->findById($categoryId);
		$supportingData = D('SupportingData', 'Logic');
		if($data['type'] == '2'){
			$res['itemWeight'] = $supportingData->getValueByKey('SHIPPING_COMMON_SHOE_WEIGHT');
		}else if($data['type'] == '1'){
			$res['itemWeight'] = $supportingData->getValueByKey('SHIPPING_COMMON_CLOTH_WEIGHT');
		}
		echo json_encode($res);
	}
	
	public function sendSubscriptionMail(){
        $res = array(
            "status" => "0"
        );
		$itemId = I('itemId');
		$itemSubscription = D('ItemSubscription', 'Logic');
		$subscriptionList = $itemSubscription->queryByItemId($itemId);
		$userInfo['userName'] = '顾客';
		$sentSbuscriptions = array();
		foreach($subscriptionList as $subscription){
			if($subscription['status'] == '1'){
				//状态为1的已经发了邮件
				continue;
			}
			$userInfo['email'] = $subscription['email'];
			if(sendMailNewVersion($mailContent, "itemSubscription", $userInfo)){
				array_push($sentSbuscriptions, $subscription['subscriptionId']);
			}
		}
		if(!empty($sentSbuscriptions)){
			$itemSubscription->batchUpdateStatus($sentSbuscriptions);
			$res['status'] = '1';
		}
		echo json_encode($res);
	}
}