<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/5/6
 * Time: 上午10:31
 */
namespace Admin\Controller;
use Think\Controller;

class BlogController extends Controller {
    public function create() {
        $this->display();
    }
    public function saveAsDraft() {
        $blogLogic = D("Blog", "Logic");
        $res = array(
            "status" => "0"
        );
        $data = array();
        $fields = array(
            "title",
            "abstract",
            "status",
            "content"
        );
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i], "");
        }
        if ($blogLogic->insertOneBlog($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
    public function saveAsPublish() {
        $blogLogic = D("Blog", "Logic");
        $res = array(
            "status" => "0"
        );
        $data = array();
        $fields = array(
            "title",
            "abstract",
            "status",
            "content"
        );
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i], "");
        }
        if ($blogLogic->insertOneBlog($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
    public function search() {
        $blogLogic = D("Blog", "Logic");
        $fields = array(
            "blog-title",
            "blog-abstract",
            "blog-content",
            "blog-createdDateStart",
            "blog-createdDateEnd",
            "blog-status"
        );
        $searchConditions = array();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i] == "blog-status") {
                $searchConditions[$fields[$i]] = I("post.".$fields[$i], "nothing");
            } else {
                $searchConditions[$fields[$i]] = I("post.".$fields[$i], "");
            }
        }
        $data = $blogLogic->searchBlogsByConditions($searchConditions);
        $this->assign("data", $data);
        $this->assign("searchConditions", $searchConditions);
        $this->assign("searchConditionsJSON", json_encode($searchConditions));
        $this->display();
    }

    public function edit() {
        $blogLogic = D("Blog", "Logic");
        $blogId = I("get.blogId", "");
        if ($blogId == "") {
            die("错误操作!");
        }
        $blogInformation = $blogLogic->getBlogInformationByBlogId($blogId);
        $blogInformation["content"] = htmlspecialchars_decode($blogInformation["content"]);
        $this->assign("blogInformationJSON", json_encode($blogInformation));
        $this->assign("blogInformation", $blogInformation);
        $this->display();
    }


    public function update() {
        $blogLogic = D("Blog", "Logic");
        $res = array(
            "status" => "0"
        );
        $data = array();
        $fields = array(
            "title",
            "abstract",
            "status",
            "content",
            "blogId"
        );
        for ($i = 0; $i < count($fields); $i++) {
            $data[$fields[$i]] = I("post.".$fields[$i], "");
        }
        if ($blogLogic->updateOneBlog($data) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }

    public function deleteOneBlogById() {
        $blogLogic = D("Blog", "Logic");
        $blogId = I("get.blogId", "");
        if ($blogId == "") {
            die("错误操作!");
        }
        $res = array(
            "status" => "0"
        );
        if ($blogLogic->deleteOneBlogById($blogId) !== false) {
            $res["status"] = "1";
        }
        echo json_encode($res);
    }
}