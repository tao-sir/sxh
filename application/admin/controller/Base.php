<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Base extends Controller
{
	public $admin_uid;
    public function _initialize(){
    	$this->view->replace([
			'__PUBLIC__' => '/public/static/admin',
			'__UPLOAD__' => '/public/uploads',
		]);
		$this->admin_uid = session('admin_uid');
	}
	public function checkLogin(){
		$admin_uid = session('admin_uid');
		if(!$admin_uid) {
			return false;
		}else {
			return true;
		}
	}
}
