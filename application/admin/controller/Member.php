<?php
namespace app\admin\controller;
use think\Db;
class Member extends Base {
    public function _initialize(){
        parent::_initialize();
        if(!$this->checkLogin()){
            $this->redirect("/admin/index/login");
        }
    }
    public function index() {
    	if(input("get.phone")){
    		$where['phone'] = input("get.phone");
    	}else{
	    	$where = true;
    	}
    	$count = Db::name("user")->where("status",'1')->where($where)->count();
		$list = Db::name("user")->where($where)->where("status",'1')->order("id DESC")->limit(10)->select();
		$this->assign("list",$list);
		$this->assign("count",$count);
        return $this->fetch();
    }

    public function page() {
    	$page = input('post.page');$page -= 1;$page *= 10;
    	$count = Db::name("user")->where("status",'1')->count();
		$list = Db::name("user")->order("id DESC")->where("status",'1')->limit($page,10)->select();
		foreach($list as $k=>$v){
			$list[$k]['regTime'] = date("Y-m-d H:i",$v['regTime']);
			$list[$k]['lastLoginTime'] = date("Y-m-d H:i",$v['lastLoginTime']);
			$list[$k]['province'] = $v['province']?$v['province']:" ";
			$list[$k]['city'] = $v['city']?$v['city']:" ";
			$list[$k]['district'] = $v['district']?$v['district']:" ";
		}
		return json($list);
    }

    public function add(){
        return $this->fetch();
    }

    public function changepass(){
    	return $this->fetch();
    }

    public function doadd(){
        $data['username'] = input('post.username');
        $data['password'] = md5(input('post.username'));
        $data['phone'] = input('post.phone');
        $data['regTime'] = time();
        $phonecheck = Db::name("user")->where("phone",$data['phone'])->find();
        $usernamecheck = Db::name("user")->where("username",$data['username'])->find();
        if($phonecheck){
            $this->error("手机号已存在");
        }
        if($usernamecheck){
            $this->error("昵称已存在");
        }
        
        Db::name("user")->insert($data);
        $this->success("添加成功");
    }
    public function dochangepass(){
        $data['id'] = input("post.id");
        $data['password'] = md5(input("post.password"));
        Db::name("user")->update($data);
        $this->success("修改成功");
    }
    public function delete(){
        $data['id'] = input("post.id");
        $data['status'] = "0";
        Db::name("user")->update($data);
        $res['status'] = 1;
        $res['msg'] = '删除成功';
        return json($res);
    }
}