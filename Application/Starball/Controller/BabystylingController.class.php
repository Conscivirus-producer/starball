<?php
namespace Starball\Controller;
use Think\Controller;
class BabystylingController extends BaseController {
	public function index(){
		$this->commonProcess();
		$blogId = I("blogId");
		$blogLogic = D("Blog", "Logic");
		$blog = $blogLogic->getBlogInformationByBlogId($blogId);
		$blog["htmlContent"] = htmlspecialchars_decode($blog["content"]);
		$this->assign('blog', $blog);
		$this->display();
	}
	
	public function contentlist(){
		$this->commonProcess();
		$blogLogic = D("Blog", "Logic");
		$map["status"] = array('EQ', '1');
		$contentList = D("Blog")->where($map)->order('lastUpdatedDt desc')->select();
		for ($i=0; $i < count($contentList); $i++) { 
			 $contentList[$i]["blogContent"] = htmlspecialchars_decode($contentList[$i]["content"]);
			 preg_match("/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png)/", $contentList[$i]["blogContent"], $contentList[$i]["imgSrc"]);  
		}
		$this->assign('contentlist', $contentList);
		$this->display();
	}
}