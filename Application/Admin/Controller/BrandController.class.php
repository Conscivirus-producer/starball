<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/25
 * Time: 下午3:09
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
use Qiniu\Auth;

class BrandController extends Controller {
    public function index() {
        $brandLogic = D("Brand", "Logic");
        $categoryLogic = D("Category", "Logic");
        $this->assign("brandData", $brandLogic->getAllBrandInfo());
        $this->assign("categoryData", $categoryLogic->getAllCategoryInfo());
        $this->display();
    }
	
	public function editBrand(){
		$brand = D('Brand', 'Logic')->getByBrandId(I('brandId'));
		$this->assign('brand', $brand);
		$this->display();
	}
	
	public function doEditBrand(){
        $res = array(
            "status" => "0"
        );
		$brandId = I('brandId');
		$discount = I('discount');
		$data['discount'] = $discount;
		D('Brand', 'Logic')->updateBrand($data, $brandId);
		
		//更新这个brand下面所有item的折扣
		D('Item', 'Logic')->updateDiscountByBrandId($brandId, $discount);
		
		$res['status'] = '1';
		echo json_encode($res);
	}

    public function deleteBrandById() {
        $res = array(
            "status" => "0"
        );
        $brandId = I("get.brandId", "");
        if ($brandId == "") {
            echo $res;
            return;
        }
        $brandLogic = D("Brand", "Logic");
        if ($brandLogic->deleteBrandById($brandId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function deleteCategoryById() {
        $res = array(
            "status" => "0"
        );
        $categoryId = I("get.categoryId", "");
        if ($categoryId == "") {
            echo $res;
            return;
        }
        $categoryLogic = D("Category", "Logic");
        if ($categoryLogic->deleteCategoryById($categoryId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function addBrand() {
        $res = array(
            "status" => "0"
        );
        $brandLogic = D("Brand", "Logic");
        $fields = array(
            "brandName",
            "description"
        );
        $data = array();
        for($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($brandLogic->addBrand($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function addCategory() {
        $res = array(
            "status" => "0"
        );
        $CategoryLogic = D("Category", "Logic");
        $fields = array(
            "categoryName",
            "type"
        );
        $data = array();
        for($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i]);
        }
        if ($CategoryLogic->addCategory($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

}