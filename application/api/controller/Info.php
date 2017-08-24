<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Image;
use app\wap\controller\Base;
class Info extends Base
{
    /*实名认证*/
    public function identify(){
        $idcard = input('post.idcard');
        $realname = input('post.realname');
        $res = Db::name('user')->where('id',$this->uid)->update(['idCard'=>$idcard,'realName'=>$realname]);
        if($res){
            $data['status'] = 1;
            $data['msg'] = '实名认证成功!';
            return json($data);
        }else {
            $data['status'] = 0;
            $data['msg'] = '实名认证失败!';
            return json($data);
        }
    }
    /*修改性别*/
    public function changesex(){
        $sex = input('post.sex');
        $res = Db::name('user')->where('id',$this->uid)->update(['sex'=>$sex]);
        if($res){
            $data['status'] = 1;
            $data['msg'] = '性别修改成功!';
            return json($data);
        }else {
            $data['status'] = 0;
            $data['msg'] = '性别修改失败!';
            return json($data);
        }
    }
    /*修改生日*/
    public function changebirth(){
    	$birth = input('post.birth');
    	$res = Db::name('user')->where('id',$this->uid)->update(['birthday'=>$birth]);
    	if($res){
    		$data['status'] = 1;
            $data['msg'] = '生日修改成功!';
            return json($data);
    	}else {
    		$data['status'] = 0;
    		$data['msg'] = '生日修改失败!';
    		return json($data);
    	}
    }

    /*修改地区*/
    public function changearea(){
        $area = explode('-',input('post.area'));
        if(count($area) == 1) {
            $p = $area[0];
            $c = "";
            $d = "";
        }elseif(count($area) == 2){
            $p = $area[0];
            $c = $area[1];
            $d = "";
        }else{
            list($p,$c,$d) = $area;
        }
        
        $res = Db::name('user')->where('id',$this->uid)->update(['province'=>$p,'city'=>$c,'district'=>$d]);
        if($res){
            $data['status'] = 1;
            $data['msg'] = '地区修改成功!';
            return json($data);
        }else {
            $data['status'] = 0;
            $data['msg'] = '地区修改失败!';
            return json($data);
        }
    }
    /*修改头像*/
    public function changeface(Request $request){
        $file = $request->file('face');
        $res = upload($file);
        if($res['status'] == 1) {
            Db::name('user')->where('id',$this->uid)->update(['face'=>$res['path']]);
        }
        echo "<script>window.location.href='/wap/member/info';</script>";
    }
    /*修改头像*/
    public function changepcface(Request $request){
        $file = $request->file('face');
        $res = upload($file);
        if($res['status'] == 1) {
            Db::name('user')->where('id',$this->uid)->update(['face'=>$res['path']]);
        }
        echo "<script>window.location.href='/member';</script>";
    }
    /*修改密码*/
    public function changepass() {
        $oldpass = md5(input('post.oldpass'));
        $newpass = md5(input('post.newpass'));
        $check = Db::name('user')->where(['id'=>$this->uid,'password'=>$oldpass])->find();
        if($check){
            Db::name('user')->where('id',$this->uid)->update(['password'=>$newpass]);
            $data['msg'] = '密码修改成功';
            $data['status'] = 1;
            return json($data);
        }else {
            $data['msg'] = '原始密码输入不正确';
            $data['status'] = 0;
            return json($data);
        }
    }

    /*PC端修改个人信息*/
    public function changeinfo(){
        $sex = input('post.sex');
        $year = input('post.year');
        $month = input('post.month');
        $day = input('post.day');
        $birthday = $year.'-'.$month.'-'.$day;
        $province = input('post.province');
        $city = input('post.city');
        $district = input('post.town');
        
        $res = Db::name('user')->where('id',$this->uid)->update(['province'=>$province,'city'=>$city,'district'=>$district,'sex'=>$sex,'birthday'=>$birthday]);
        if($res){
            $data['status'] = 1;
            $data['msg'] = '个人信息更新成功!';
            return json($data);
        }else {
            $data['status'] = 0;
            $data['msg'] = '个人信息更新失败!';
            return json($data);
        }
    }
}
