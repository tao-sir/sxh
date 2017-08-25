<?php
namespace app\wap\controller;
use think\Db;
class Glory extends Base
{
	public function _initialize(){
        parent::_initialize();
        $info = Db::name('user')->where('id',$this->uid)->find();
        $this->assign($info);
    }
    public function index()
    {
        $myteam = Db::name('glory_member')->where('uid',$this->uid)->find();
        $this->assign("myteam",$myteam);
        return $this->fetch();
    }

    public function myteam(){
        if(!$this->checkLogin()){
            $this->redirect("/wap/member/login");
        }
        $myteam = Db::name('glory_member')->where('uid',$this->uid)->find();
        $team = Db::name('glory_member m')->where('tid',$myteam['tid'])->join("__USER__ u","m.uid=u.id")->field("m.*,u.username,u.phone,u.face")->order("is_captain ASC")->select();
        $this->assign("team",$team);
        return $this->fetch();
    }

    public function primary(){
        $allteam = Db::name("glory_battle")->where(['round'=>1])->select();
        $mainteam = Db::name("glory_battle")->where(['round'=>1,'main'=>'1'])->select();
        $winteam = Db::name("glory_battle")->where(['round'=>1,'winner'=>'l'])->order("win_time DESC")->select();
        $this->assign('allteam',$allteam);
        $this->assign('mainteam',$mainteam);
        $this->assign('winteam',$winteam);
        return $this->fetch();
    }

    public function semi(){
        $teamlist = Db::name('glory_battle')->where('round','2')->where('win_time','>','0')->select();
        $vslist = Db::name('glory_battle')->where('round','in','2,3,4')->where(['main'=>'1'])->select();
        $winlist = Db::name('glory_battle')->where('round','in','3,4')->where(['winner'=>'l'])->select();
        $this->assign("teamlist",$teamlist);
        $this->assign("vslist",$vslist);
        $this->assign("winlist",$winlist);
        $this->assign("count",Db::name("user")->count());
        return $this->fetch();
    }

    public function finals(){
        $list16 = Db::name('glory_battle')->where(['round'=>'5','main'=>'1'])->select();
        $list8 = Db::name('glory_battle')->where(['round'=>'6','main'=>'1'])->select();
        $list4 = Db::name('glory_battle')->where(['round'=>'7','main'=>'1'])->select();
        $list1 = Db::name('glory_battle')->where(['round'=>'8','main'=>'1'])->select();
        $list2 = Db::name('glory_battle')->where(['round'=>'9','main'=>'1'])->select();
        $this->assign('list16',$list16);
        $this->assign('list8',$list8);
        $this->assign('list4',$list4);
        $this->assign('list2',$list2);
        $this->assign('list1',$list1);
        return $this->fetch();
    }
}
