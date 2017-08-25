<?php
namespace app\index\controller;
use think\Db;
class Lover extends Base
{
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
}
