<?php
namespace app\index\controller;
use think\Db;
class Glory extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function primary() {
        if(ismobile()){
            $this->redirect('/wap/glory/primary');
        }else {
        	$teamlist = Db::name('glory_battle')->where(['round'=>'1'])->select();
        	$vslist = Db::name('glory_battle')->where(['round'=>'1','main'=>'1'])->select();
        	$winlist = Db::name('glory_battle')->where(['round'=>'1','winner'=>'l'])->select();
        	$this->assign("teamlist",$teamlist);
        	$this->assign("vslist",$vslist);
        	$this->assign("winlist",$winlist);
        	return $this->fetch();
        }
    }

    public function semi() {
        if(ismobile()){
            $this->redirect('/wap/glory/semi');
        }else {
        	$teamlist = Db::name('glory_battle')->where('round','2')->where('win_time','>','0')->select();
        	$vslist = Db::name('glory_battle')->where('round','in','2,3,4')->where(['main'=>'1'])->select();
        	$winlist = Db::name('glory_battle')->where('round','in','3,4')->where(['winner'=>'l'])->select();
        	$this->assign("teamlist",$teamlist);
        	$this->assign("vslist",$vslist);
        	$this->assign("winlist",$winlist);
        	return $this->fetch();
        }
    }

    public function finals() {
        if(ismobile()){
            $this->redirect('/wap/glory/finals');
        }else {
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
}
