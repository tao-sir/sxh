<?php
namespace app\wap\controller;
use think\Db;
class Lover extends Base
{
    public function _initialize(){
        parent::_initialize();
        $info = Db::name('user')->where('id',$this->uid)->find();
        $this->assign($info);
    }

    public function index(){
        $list = Db::name("lover_score")->order("score DESC")->limit(6)->select();
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function sign(){
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        $voteinfo = Db::name("lover_score")->where("uid",$this->uid)->find();
        if($voteinfo){
            $this->redirect("/wap/lover/detail/".$voteinfo['id']);
        }
        return $this->fetch();
    }

    public function loverlist($id=0){
        if($id != 0){
            $list = Db::name("lover_score")->where("id",$id)->select();
        }else{
            $list = Db::name("lover_score")->order("addtime DESC")->select();
        }
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function detail($id=0){
        $this->assign(Db::name("lover_score")->find($id));
        return $this->fetch();
    }

    public function login(){
        if(cookie('phone')) {
            $remember = 'checked';
        }else {
            $remember = '';
        }
        $this->assign('remember',$remember);
        return $this->fetch();
    }
    public function quicklogin(){
        if($this->checkLogin()){
            $this->redirect("/wap/member");
        }
        return $this->fetch();
    }

    public function register() {
        if($this->checkLogin()){
            $this->redirect("/wap/member");
        }
    	return $this->fetch();
    }

    public function forget() {
        if($this->checkLogin()){
            $this->redirect("/wap/member");
        }
        return $this->fetch();
    }

    public function bind() {
        if($this->checkLogin()){
            $this->redirect("/wap/member");
        }
    	return $this->fetch();
    }

    public function info(){
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        return $this->fetch();
    }

    public function identify(){
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        return $this->fetch();
    }

    public function mycode() {
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        $tid = Db::name('glory_member')->where("uid",$this->uid)->value('tid');
        if($tid){
            $team = Db::name('glory_battle')->where(["left_tid"=>$tid,"round"=>5])->find();
            if($team){
                $this->assign("team_number",$team['left_number']);
                $this->assign("codetype",'team');
            }else{
                $this->assign("codetype",'person');
            }
        }else{
            $this->assign("codetype",'person');
        }
        return $this->fetch();
    }

    public function changepass() {
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        return $this->fetch();
    }
}
