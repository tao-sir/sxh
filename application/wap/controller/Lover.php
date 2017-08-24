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
        $this->assign("desc",strtotime("2017-09-09 23:59:59")-time());
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
        $this->assign("desc",strtotime("2017-09-09 23:59:59")-time());
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function detail($id=0){
        $this->assign(Db::name("lover_score")->find($id));
        return $this->fetch();
    }
}
