<?php
namespace app\index\controller;
use think\Controller;
class Base extends Controller
{
	public $uid;
    public function _initialize(){
    	$this->view->replace([
			'__PUBLIC__' => '/public/static/home',
			'__UPLOAD__' => '/public/uploads',
		]);
		$this->uid = session('uid');
		if($this->needLogin && !$this->uid) {
			$this->redirect("/login");
		}
		$this->assign("uid",$this->uid);
	}

	public function checkLogin(){
		$uid = session('uid');
		if(!$uid) {
			return false;
		}else {
			return true;
		}
	}
}
