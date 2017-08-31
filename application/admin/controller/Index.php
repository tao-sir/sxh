<?php
namespace app\admin\controller;
use think\Db;
class Index extends Base {
    public function index() {
        if(strpos($_SERVER["REQUEST_URI"],'admin') !== false){
            echo "<style>body{background:rgb(255,255,255)}</style><span style='font-size:260px;'>您迷路了？</span>";exit;
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
        $this->assign("count",Db::name("user")->count());
        $bt = strtotime(date("Y-m-d 00:00:00"));
        $ot = strtotime(date("Y-m-d 23:59:59"));
        $this->assign("todayCount",Db::name("user")->where('regTime','between',[$bt,$ot])->count());
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

    public function sendSMS(){
        if(!$this->checkLogin()){
            $this->redirect("login");
        }
        $templetsId = input('get.id');
        $phonelist = Db::name("user")->order("id ASC")->field("phone")->select();
        foreach($phonelist as $v){
            $phones[] = $v['phone'];
        }
        $phones = array_chunk($phones, 20);
        foreach($phones as $phone){
            sendTempletsSMS($phone,$templetsId);
        }
    }
}