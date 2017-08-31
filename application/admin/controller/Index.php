<?php
namespace app\admin\controller;
use think\Db;
class Index extends Base {
    public function index() {
        if(strpos($_SERVER["REQUEST_URI"],'admin') !== false){
            echo "<html><head><meta charset='UTF-8'></head><style>body {animation: b 0.1s linear infinite; } p {text-align:center;position:absolute;top:20%;font-size: 260px; color: #000; animation: a 0.1s linear infinite; /*box-shadow: 0px 0px 10px #999;*/ } @keyframes a{0 {text-shadow: 0px 0px 200px white;} 20% {text-shadow: 0px 0px 400px blue;} 30% {text-shadow: 0px 0px 600px green;} 40% {text-shadow: 0px 0px 800px yellow;} 50% {text-shadow: 0px 0px 1000px red;} 60% {text-shadow: 0px 0px 800px orangered;} 80% {text-shadow: 0px 0px 600px green;} 90% {text-shadow: 0px 0px 400px blue;} 100% {text-shadow: 0px 0px 200px white;} 0 {color: white;} 20% {color: blue;} 30% {color:green;} 40% {color:yellow;} 50% {color:red;} 60% {color:orangered;} 80% {color:green;} 90% {color:blue;} 100% {color:white;} } @keyframes b{0 {background-color: white;} 20% {background-color: blue;} 30% {background-color:green;} 40% {background-color:orangered;} 50% {background-color:yellow;} 60% {background-color:green;} 80% {background-color:red;} 90% {background-color:blue;} 100% {background-color:white;} }</style><p>您迷路了？</p></body></html>";exit;
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