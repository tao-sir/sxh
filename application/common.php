<?php
use org\util\SMS;
use think\Image;
use think\Db;
use think\Cache;
function getUserName($uid){
	return Db::name("user")->where("id",$uid)->value('username');
}
//判断是手机登录还是电脑登录  
function ismobile() {  
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备  
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
		return true;
	}
	//此条摘自TPM智能切换模板引擎，适合TPM开发  
	if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT']){
		return true;
	}
	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA'])){
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	}
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array(
			'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
		);
		//从HTTP_USER_AGENT中查找手机浏览器的关键字  
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备  
		// 如果支持wml和html但是wml在html之前则是移动设备  
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}
/*文件上传*/
function upload($file){
	// 获取表单上传文件
	if (empty($file)) {
		$res['msg'] = '请选择上传文件';
		$res['status'] = 0;
		return $res;
	}
	$image = Image::open($file);
	$fix[] = $image->width();
	$fix[] = $image->height();
	sort($fix);
	$image->thumb($fix[0], $fix[0], Image::THUMB_CENTER);
	$image->thumb(400, 400, Image::THUMB_SCALING);
	// 移动到框架应用根目录/public/uploads/ 目录下
	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	$saveName = '/'.date('Ymd').'/thumb_'.$info->getFileName();
	$image->save(ROOT_PATH . 'public' . DS . 'uploads' . $saveName);
	if ($info) {
		$res['msg'] = '文件上传成功';
		$res['path'] = $saveName;
		$res['status'] = 1;
		return $res;
	} else {
		// 上传失败获取错误信息
		$res['msg'] = $file->getError();
		$res['status'] = 0;
		return $res;
	}
}

/*发送短信*/
function sendSMS($mobile){
	//网易云信分配的账号，请替换你在管理后台应用下申请的Appkey
    $AppKey = '1d05c0b46ea37b070bf53fe41aa55603';
    //网易云信分配的账号，请替换你在管理后台应用下申请的appSecret
    $AppSecret = '2ee5e5fe91af';
    $p = new SMS($AppKey,$AppSecret,'curl');     //fsockopen伪造请求
    $ret = $p->sendSMSCode(3057860,$mobile,6);
    $verifyCode = $ret['obj'];
    session('code',$verifyCode);
    session('phone',$mobile);
    $res['msg'] = "发送成功";
    $res['status'] = 1;
    return $res;
}

/*发送模板短信*/
function sendTempletsSMS($phones,$tempid=''){
    $AppKey = '1d05c0b46ea37b070bf53fe41aa55603';
    $AppSecret = '2ee5e5fe91af';
    $p = new SMS($AppKey,$AppSecret,'curl');     //fsockopen伪造请求
    $p->sendSMSTemplate($tempid,$phones,array('notice'));
}

/*获取底部文章栏目*/
function getArticleType(){
	return Db::name("article_type")->where(['footer_show'=>'1','status'=>'1'])->select();
}

/*获取底部文章*/
function getArticle($tid){
	return Db::name("article")->where(['tid'=>$tid])->select();
}

function getSign(){
	if(!Cache::get('access_token')){
		$token = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxd234e21357e1ee43&secret=d127194a6598e74e6320f46945c0e5a9"),true);
		Cache::set('access_token',$token['access_token'],7200);
	}
	$access_token = Cache::get('access_token');
	if(!Cache::get('jsapi_ticket')){
		$ticket = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi"),true);
		Cache::set('jsapi_ticket',$ticket['ticket'],7200);
	}
	$jsapi_ticket = Cache::get('jsapi_ticket');
	// 1. 对加密数组进行字典排序
	$array['noncestr'] = md5(time());
	$array['jsapi_ticket'] = $jsapi_ticket;
	$array['timestamp'] = time();
	$array['url'] = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	foreach ($array as $key=>$value){
		$arr[$key] = $key; 
	}
	sort($arr); //字典排序

	//KV对
	$str = "";
	foreach ($arr as $k => $v) {
		$str = $str."&".$arr[$k]."=".$array[$v];
	}
	$str = substr($str,1);
	$sign = sha1($str);
	$array['signature'] = $sign;
	$array['domain'] = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"];
	return $array;
}