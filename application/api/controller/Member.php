<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use org\qqConnect\QC;
class Member extends Controller
{
    public function forget(){
        $phone = input('post.phone');
        $code = input('post.code');
        $password = md5(input('post.password'));
        if($phone != session('phone')){
            $res['msg'] = '手机号与验证码不匹配';
            $res['status'] = 0;
            return $res;
        }
        if($code != session('code')){
            $res['msg'] = '短信验证码输入错误';
            $res['status'] = 0;
            return $res;
        }
        $upres = Db::name('user')->where('phone',$phone)->update(['password'=>$password]);
        if($upres){
            $res['msg'] = '密码修改成功 请使用新密码登录';
            $res['status'] = 1;
            return $res;
        }else{
            $res['msg'] = '密码修改失败';
            $res['status'] = 0;
            return $res;
        }
    }

    public function dologin(){
    	$phone = input('post.phone');
    	$password = md5(input('post.password'));
    	$res = Db::name('user')->where(['phone'=>$phone,'password'=>$password])->find();
    	if($res){
    		session('uid',$res['id']);
    		session('phone',$res['phone']);
    		session('username',$res['username']);
    		$data['status'] = 1;
    		$data['msg'] = '登录成功';
            $lastLoginTime['lastLoginTime'] = time();
            Db::name('user')->where('id',$res['id'])->update($lastLoginTime);
            if(input('post.remember') == '1'){
                cookie('phone',$phone);
                cookie('password',input('post.password'));
            }else {
                cookie('phone',null);
                cookie('password',null);
            }
            $update['qqOpenId'] = session('qqOpenId') ? session('qqOpenId') : '';
            $update['wxOpenId'] = session('wxOpenId') ? session('wxOpenId') : '';
            $update['mpOpenId'] = session('mpOpenId') ? session('mpOpenId') : '';
            if($update['qqOpenId'] == ''){
                unset($update['qqOpenId']);
            }
            if($update['wxOpenId'] == ''){
                unset($update['wxOpenId']);
            }
            if($update['mpOpenId'] == ''){
                unset($update['mpOpenId']);
            }
            if(!empty($update['qqOpenId']) || !empty($update['wxOpenId']) || !empty($update['mpOpenId'])){
                $bindret = Db::name('user')->where('id',$res['id'])->update($update);
                if($bindret){
                    $data['msg'] = '绑定成功';
                    $data['status'] = 1;
                    return json($data);
                }else {
                    $data['msg'] = '绑定失败';
                    $data['status'] = 0;
                    return json($data);
                }
            }
    		return json($data);
    	}else {
    		$data['status'] = 0;
    		$data['msg'] = '用户名或密码错误!';
    		return json($data);
    	}
    }

    public function quicklogin(){
        $phone = input('post.phone');
        $code = input('post.code');
        if($phone != session('phone')){
            $res['msg'] = '手机号与验证码不匹配';
            $res['status'] = 0;
            return $res;
        }
        if($code != session('code')){
            $res['msg'] = '短信验证码输入错误';
            $res['status'] = 0;
            return $res;
        }
        $res = Db::name('user')->where(['phone'=>$phone])->find();
        if($res){
            session('uid',$res['id']);
            session('phone',$res['phone']);
            session('username',$res['username']);
            $data['status'] = 1;
            $data['msg'] = '登录成功';
            $lastLoginTime['lastLoginTime'] = time();
            Db::name('user')->where('id',$res['id'])->update($lastLoginTime);
            return json($data);
        }else {
            $data['status'] = 0;
            $data['msg'] = '未找到用户!';
            return json($data);
        }
    }

    public function logout() {
    	session(null);
    	$this->redirect('/');
    }

    public function doreg() {
        $username = input('post.username');
        $phone = input('post.phone');
        $password = input('post.password');
        $code = input('post.code');
        if($phone != session('phone')){
            $res['msg'] = '手机号与验证码不匹配';
            $res['status'] = 0;
            return $res;
        }
        if($code != session('code')){
            $res['msg'] = '短信验证码输入错误';
            $res['status'] = 0;
            return $res;
        }
        $namecheck = Db::name('user')->where('username',$username)->find();
        if($namecheck){
            $res['msg'] = '昵称已存在';
            $res['status'] = 0;
            return $res;
        }
        $phonecheck = Db::name('user')->where('phone',$phone)->find();
        if($phonecheck){
            $res['msg'] = '手机号已存在';
            $res['status'] = 0;
            return $res;
        }
        $data['qqOpenId'] = session('qqOpenId') ? session('qqOpenId') : '';
        $data['wxOpenId'] = session('wxOpenId') ? session('wxOpenId') : '';
        $data['mpOpenId'] = session('mpOpenId') ? session('mpOpenId') : '';
        if($data['qqOpenId'] == ''){
            unset($data['qqOpenId']);
        }
        if($data['wxOpenId'] == ''){
            unset($data['wxOpenId']);
        }
        if($data['mpOpenId'] == ''){
            unset($data['mpOpenId']);
        }
        $data['username'] = $username;
        $data['phone'] = $phone;
        $data['password'] = md5($password);
        $data['regTime'] = time();
        $data['lastLoginTime'] = time();
        $insert = Db::name('user')->insert($data);
        if($insert){
            $uid = Db::name('user')->where('phone',$phone)->value('id');
            session('uid',$uid);
            session('phone',$phone);
            session('username',$username);
            session('qqOpenId',null);
            session('wxOpenId',null);
            session('mpOpenId',null);
            $res['msg'] = '注册成功';
            $res['status'] = 1;
            return $res;
        }
    }

    public function getcode(){
        $find = input('post.find');
        $phone = input('post.phone');
        if($find == 1) {
            $user = Db::name('user')->where('phone',$phone)->find();
            if(!$user){
                $res['status'] = 0;
                $res['msg'] = '您的手机号尚未在本站注册';
                return json($res);
            }
        }else {
            $user = Db::name('user')->where('phone',$phone)->find();
            if($user){
                $res['status'] = 0;
                $res['msg'] = '您的手机号已注册过本站!';
                return json($res);
            }
        }
        sendSMS(input('post.phone'));
        $res['status'] = 1;
        return json($res);
    }

    public function oauth2(){
        $token = json_decode(file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxd234e21357e1ee43&secret=d127194a6598e74e6320f46945c0e5a9&code=".input('get.code')."&grant_type=authorization_code"),true);
        $user = Db::name('user')->where('mpOpenId',$token['openid'])->find();
        if($user){
            session('uid',$user['id']);
            session('phone',$user['phone']);
            session('username',$user['username']);
            $lastLoginTime['lastLoginTime'] = time();
            Db::name('user')->where('id',$user['id'])->update($lastLoginTime);
            if(ismobile()){
                $this->redirect('/wap/member');
            }else {
                $this->redirect('/member');
            }
        }else{
            session('mpOpenId',$token['openid']);
            if(ismobile()){
                $this->redirect('/wap/member/bind');
            }else {
                $this->redirect('/bind');
            }
        }
    }

    public function qqoauth2(){
        $qc = new QC();
        $access_token = $qc->qq_callback();
        $openid = $qc->get_openid();
        $user = Db::name('user')->where('qqOpenId',$openid)->find();
        if($user){
            session('uid',$user['id']);
            session('phone',$user['phone']);
            session('username',$user['username']);
            $lastLoginTime['lastLoginTime'] = time();
            Db::name('user')->where('id',$user['id'])->update($lastLoginTime);
            if(ismobile()){
                $this->redirect('/wap/member');
            }else {
                $this->redirect('/member');
            }
        }else{
            session('qqOpenId',$openid);
            if(ismobile()){
                $this->redirect('/wap/member/bind');
            }else {
                $this->redirect('/bind');
            }
        }
    }

    public function wechatauth(){
        $code = input('get.code');
        $state = input('get.state');
        $appid = 'wxcd7c5c0e64b1bf3e';
        $appsecret = '6169b4881d777fc5f2579825c77aa452';
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $token = json_decode(file_get_contents($token_url));
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
        $access_token = json_decode(file_get_contents($access_token_url));
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
        $user_info = json_decode(file_get_contents($user_info_url));
        $resource =  json_decode(json_encode($user_info),true);
        $openid = $resource['openid'];
        $user = Db::name('user')->where('wxOpenId',$openid)->find();
        if($user){
            session('uid',$user['id']);
            session('phone',$user['phone']);
            session('username',$user['username']);
            $lastLoginTime['lastLoginTime'] = time();
            Db::name('user')->where('id',$user['id'])->update($lastLoginTime);
            if(ismobile()){
                $this->redirect('/wap/member');
            }else {
                $this->redirect('/member');
            }
        }else{
            session('wxOpenId',$openid);
            if(ismobile()){
                $this->redirect('/wap/member/bind');
            }else {
                $this->redirect('/bind');
            }
        }
    }

    public function qqlogin(){
        $qc = new QC();
        $this->redirect($qc->qq_login());
    }
}