<?php
namespace app\wap\controller;
use think\Controller;
class Base extends Controller
{
	public $uid;
    public function _initialize(){
    	$this->view->replace([
			'__PUBLIC__' => '/public/static/wap',
			'__UPLOAD__' => '/public/uploads',
		]);
		$this->uid = session('uid');
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
