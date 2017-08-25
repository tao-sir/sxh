<?php
namespace app\index\controller;
use think\Db;
class Index extends Base
{
    public function index()
    {
    	if(ismobile()){
            $this->redirect('/lover');
        }else{
	    	$list = Db::name("lover_score")->order("score DESC")->limit(6)->select();
	        $this->assign("list",$list);
	        $this->assign("desc",strtotime("2017-09-09 23:59:59")-time());
	        return $this->fetch();
	    }
    }
}
