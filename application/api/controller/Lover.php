<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Image;
use app\wap\controller\Base;
class Lover extends Base
{   
    public function vote(){
        $id = input('post.id');
        // $id = 1;
        if(!$this->checkLogin()){
            $res['status'] = 2;
            $res['msg'] = "请先登录";
            return json($res);
        }
        $lover = Db::name("lover_score")->find($id);
        if($lover['uid'] == $this->uid){
            $res['status'] = 0;
            $res['msg'] = "您无法给自己投票!";
            return json($res);
        }
        $bt = strtotime(date("Y-m-d 00:00:00"));
        $ot = strtotime(date("Y-m-d 23:59:59"));
        $count = Db::name("lover_log")->where('time','between',[$bt,$ot])->where("uid",$this->uid)->count();
        if($count >= 5){
            $res['status'] = 0;
            $res['msg'] = "您今日已投票5次，请明日重试!";
            return json($res);
        }
        $surplus = 4 - $count;
        Db::name("lover_score")->where("id",$id)->setInc("score");
        $data['uid'] = $this->uid;
        $data['lid'] = $id;
        $data['time'] = time();
        Db::name("lover_log")->insert($data);
        $res['status'] = 1;
        $res['msg'] = "投票成功,您今日还可投".$surplus."票!";
        return json($res);
    }

    /*上传图片*/
    public function sign(Request $request){
        $file = $request->file('image');
        $res = upload($file);
        $data['image'] = $res['path'];
        $data['owner'] = input("post.owner");
        $data['lover'] = input("post.lover");
        $data['content'] = input("post.content");
        $data['addtime'] = time();
        $data['uid'] = $this->uid;
        $data['score'] = 0;
        Db::name("lover_score")->insert($data);
        $id = Db::name("lover_score")->where("uid",$this->uid)->value("id");
        echo "<script>window.location.href='/wap/lover/detail/".$id."';</script>";
    }
}
