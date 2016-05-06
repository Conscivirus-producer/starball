<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/5/6
 * Time: 下午12:11
 */
namespace Common\Logic;
use Common\Model\BlogModel;
class BlogLogic extends BlogModel{
    public function insertOneBlog($data) {
        $data["createdDt"] = date("Y-m-d H:i:s" ,time());
        $data["lastUpdatedDt"] = date("Y-m-d H:i:s" ,time());
        return ($this->add($data) !== false);
    }
    public function searchBlogsByConditions($searchConditions) {
        $map = array();
        $createdDateStart = "2014-10-02";
        if ($searchConditions["blog-createdDateStart"] != "") {
            $createdDateStart = $searchConditions["blog-createdDateStart"];
        }
        $createdDateEnd = date("Y-m-d" ,time() + 24*60*60);
        if ($searchConditions["blog-createdDateEnd"] != "") {
            $createdDateEnd = $searchConditions["blog-createdDateEnd"];
        }
        $map["createdDt"]  = array(array('EGT',$createdDateStart),array('ELT',$createdDateEnd),'and');
        $map["title"] = array('like', "%".trim($searchConditions["blog-title"])."%");
        $map["abstract"] = array('like', "%".trim($searchConditions["blog-abstract"])."%");
        $map["content"] = array('like', "%".trim($searchConditions["blog-content"])."%");
        if ($searchConditions["blog-status"] != "nothing") {
            $map["status"] = $searchConditions["blog-status"];
        }
        return $this->where($map)->order('createdDt desc')->select();
    }
    public function getBlogInformationByBlogId($blogId) {
        $map["blogId"] = $blogId;
        $blogInformation = $this->where($map)->select();
        return current($blogInformation);
    }
    public function updateOneBlog($data) {
        $data["lastUpdatedDt"] = date("Y-m-d H:i:s" ,time());
        return ($this->save($data) !== false);
    }
}