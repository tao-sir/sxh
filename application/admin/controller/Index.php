<?php
namespace app\admin\controller;
use think\Db;
class Index extends Base {
    public function index() {
        if(strpos($_SERVER["REQUEST_URI"],'admin') !== false){
            echo "瞎tm试什么你试？";exit;
        }
        if(!$this->checkLogin()){
            $this->redirect("login");
        }
        $this->assign(Db::name("admin")->find($this->admin_uid));
        return $this->fetch();
    }
    public function welcome() {
    	if(!$this->checkLogin()){
			$this->redirect("login");
		}
        $this->assign(Db::name("admin")->find($this->admin_uid));
    	$this->assign($_SERVER);
    	return $this->fetch();
    }

    public function login(){
        if($this->checkLogin()){
            $this->redirect("/zeichangdehoutaidizhi");
        }
    	return $this->fetch();
    }

    public function dologin(){
        $where['username'] = input("post.username");
        $where['password'] = md5(input("post.password"));
        $admin = Db::name("admin")->where($where)->find();
        if($admin){
            Db::name("admin")->where($where)->setInc('loginTime');
            $update['lastLoginIp'] = $_SERVER['REMOTE_ADDR'];
            $update['lastLoginTime'] = time();
            Db::name("admin")->where($where)->update($update);
            session('admin_uid',$admin['id']);
            $res['status'] = 1;
            $res['msg'] = '登录成功<br /><br />欢迎归来';
            return json($res);
        }else{
            $res['status'] = 0;
            $res['msg'] = '登录失败';
            return json($res);
        }
    }

    public function logout(){
        session("admin_uid",null);
        $this->redirect("/zeichangdehoutaidizhi");
    }
}