<?php
namespace app\index\controller;
use think\Db;
class Member extends Base
{
    public function _initialize(){
        parent::_initialize();
        $info = Db::name('user')->where('id',$this->uid)->find();
        if($info){
            $this->assign($info);
        }
    }

	public function index() {
		if(!$this->checkLogin()){
			$this->redirect("/login");
		}
		if(ismobile()){
            $this->redirect('/wap/member');
        }else {
            $info = Db::name('user')->where('id',$this->uid)->find();
            if($info['birthday']){
                $birthday = explode('-',$info['birthday']);
                if($birthday) {
                    $birth['year'] = $birthday[0];
                    $birth['month'] = $birthday[1];
                    $birth['day'] = $birthday[2];
                    $this->assign($birth);
                }
            }else {
                $birth['year'] = 2000;
                $birth['month'] = 01;
                $birth['day'] = 01;
                $this->assign($birth);
            }
            $myteam = Db::name('glory_member')->where('uid',$this->uid)->find();
            $this->assign('myteam',$myteam);
            $team = Db::name('glory_member m')->where('tid',$myteam['tid'])->join("__USER__ u","m.uid=u.id")->field("m.*,u.username,u.phone,u.face")->order("is_captain ASC")->select();
            $this->assign("team",$team);
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
	}
    public function register()
    {
    	if($this->checkLogin()){
			$this->redirect("/member");
		}
		if(ismobile()){
            $this->redirect('/wap/member/register');
        }else {
			return $this->fetch();
        }
    }
    public function login(){
        if($this->checkLogin()){
            $this->redirect("/member");
        }
        if(cookie('phone')) {
            $remember = 'checked';
        }else {
            $remember = '';
        }
    	if(ismobile()){
            $this->redirect('/wap/member/login');
        }else {
            $this->assign('remember',$remember);
            return $this->fetch();
        }
    }
    public function bind(){
        if($this->checkLogin()){
            $this->redirect("/member");
        }
        return $this->fetch();
    }
    public function seven_sign(){
        $voteinfo = Db::name("lover_score")->where("uid",$this->uid)->find();
        if($voteinfo){
            $this->redirect("/member/seven_detail");
        }
    	if(!$this->checkLogin()){
            $this->redirect("/login");
        }
        if(ismobile()){
            $this->redirect('/lover');
        }else{
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
    }
    public function seven_detail(){
        if(!$this->checkLogin()){
            $this->redirect("/login");
        }
        if(ismobile()){
            $this->redirect('/lover');
        }else{
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
            $this->assign(Db::name("lover_score")->where("uid",$this->uid)->find());
            return $this->fetch();
        }
       
    }
}
