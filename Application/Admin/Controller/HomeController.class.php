<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class HomeController extends Controller {
    public function index() {
        $this->display();
    }

    public function login() {
        $this->display();
    }

    public function doLogin() {
        $adminUserLogic = D("AdminUser", "Logic");
        $data = array();
        $username = trim(I("post.username", ""));
        $password = trim(I("post.password", ""));
        $data["username"] = $username;
        $data["password"] = $password;
        if ($adminUserLogic->checkUserExistence($data) === true) {
            session('starball_kid_username_fuck_you_hacker',$username);
            redirect(".");
        } else {
            redirect("./login");
        }
    }

    public function logOut() {
        session(null);
        redirect("./login");
    }
}