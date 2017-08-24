<?php
namespace app\index\controller;
use think\Db;
class Index extends Base
{
    public function index()
    {
    	$myteam = Db::name('glory_member')->where('uid',$this->uid)->find();
    	$teaminfo = Db::name('glory_team')->where('id',$myteam['tid'])->find();
        $team = Db::name('glory_member m')->where('tid',$myteam['tid'])->join("__USER__ u","m.uid=u.id")->field("m.*,u.username,u.phone,u.face")->order("is_captain ASC")->select();
        $this->assign("teaminfo",$teaminfo);
        $this->assign("team",$team);
        return $this->fetch();
    }
}
