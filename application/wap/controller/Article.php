<?php
namespace app\wap\controller;
use think\Db;
class Article extends Base
{
	public function index($id=0) {
        $article = Db::name("article")->field("id,title,img,addtime,description")->where(['tid'=>$id])->select();
        $title = Db::name("article_type")->where('id',$id)->value("name");
        $this->assign("title",$title);
        $this->assign("list",$article);
        return $this->fetch();
    }

    public function detail($id=0) {
        $article = Db::name("article")->find($id);
        Db::name("article")->where('id',$id)->setInc('browser');
        $article['content'] = htmlspecialchars_decode($article['content']);
        $this->assign('article',$article);
        return $this->fetch();
    }
}
