<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Topic as TopicModel;
use app\index\model\Tag as TagModel;
use app\index\model\Topic_tag as TopicTagModel;
use app\index\model\Praise as PraiseModel;
use app\index\model\Reply as ReplyModel;
class Topic extends Controller
{   
    public function newtopic()
    {
    	if(request()->isPost()){
        $postData=input('post.');
        $topic=new TopicModel();
        $user=session('user');
        $topic->title=$postData['title'];
        $topic->content=$postData['content'];
        $topic->user_id=$user->id;
        $topic->category_id=$postData['category_id'];
        $topic->created_at=intval(microtime(true));
        $topic->save();
    	
    	$tags=$postData['tags'];
    	foreach ($tags as $value) {
    		if(is_numeric($value)){
    			$this->createTopicTag($value,$topic->id);
                continue;
    		}
    		$newTag=$this->createTag($value);
    		$this->createTopicTag($newTag->id,$topic->id);
    		
    	}
        $this->success('恭喜，帖子创建成功!');
    	
    }   
        $this->assign(['user'=>session('user'),'tag'=>TagModel::all()]);
        return $this->fetch();
    }
    public function createTopicTag($tagId,$topicId){
          $TopicTag=new TopicTagModel();
          $TopicTag->topic_id=$topicId;
          $TopicTag->tag_id=$tagId;
          $TopicTag->save();
    }
    public function createTag($name){
    	$tag=new TagModel();
    	$tag->name=$name;
    	$tag->save();
    	return $tag;
    }
    public function detail(){
        $topicId=intval(input('get.id'));
        $topic=TopicModel::getTopic($topicId);
        $replies=ReplyModel::where(['topic_id'=>$topicId])->select();
        $user=session('user');
        $this->assign([
            'topic'=>$topic,
            'user'=>$user,
            'categoryNames'=>getCategoryNames($topic->category_id),
            'topicTags'=>TopicTagModel::getTopicTagsByTopicId($topic->id),
            'replies'=>$replies,]);
        return $this->fetch();
    }
    public function praise(){
        $user=session('user');
        if(!$user){
            return ;
        }
        $topicId=intval(input('get.topicId'));
        $praise=PraiseModel::get(['user_id'=>$user->id,'topic_id'=>$topicId]);
        if($praise){
            $praise->delete();
        }else{
        $praise=new PraiseModel();
        $praise->user_id=$user->id;
        $praise->topic_id=$topicId;
        $praise->create_at=time();
        $praise->save();
        }
 
    }

    public function index(){
        $page=input('?get.page')?input('get.page'):1;
        //[$page,$showPages,$pageNum]
        $pageInfo=TopicModel::getPageInfo($page);
        $topics=TopicModel::getTopics($page);
        $hotTags=TopicTagModel::getHotTags(config('hotTagsNum'));
        $this->assign(['topics'=>$topics,'user'=>session('user'),'page'=>$pageInfo['page'],'showPages'=>$pageInfo['showPages'],'pageNum'=>$pageInfo['pageNum'],'hotTags'=>$hotTags]);
        echo $this->fetch('index');
    }

    public function search(){
        $keyword=input('get.keyword');
        $topics=TopicModel::search($keyword);
        $hotTags=TopicTagModel::getHotTags(config('hotTagsNum'));
        $this->assign(['page'=>1,'showPages'=>[1],'pageNum'=>1,'topics'=>$topics,'user'=>session('user'),'hotTags'=>$hotTags]);
        echo $this->fetch('index');
    }

    public function tag(){
        $tagId=input('get.tag');
        $topicIds=TopicTagModel::where(['tag_id'=>$tagId])->column('topic_id');
        $topics=TopicModel::getTagTopics($topicIds);
        $hotTags=TopicTagModel::getHotTags(config('hotTagsNum'));
        $this->assign(['page'=>1,'showPages'=>[1],'pageNum'=>1,'topics'=>$topics,'user'=>session('user'),'hotTags'=>$hotTags]);
        echo $this->fetch('index');
    }

    public function latest(){
        $topics=TopicModel::withCount(['praises','replies'])->order('create_at desc')->select();
        $hotTags=TopicTagModel::getHotTags(config('hotTagsNum'));
        $this->assign(['page'=>1,'showPages'=>[1],'pageNum'=>1,'topics'=>$topics,'user'=>session('user'),'hotTags'=>$hotTags]);
        echo $this->fetch('index');
    }

    public function mostPraises(){
        $topics=TopicModel::withCount(['praises','replies'])->order('praises_count desc')->select();
        $hotTags=TopicTagModel::getHotTags(config('hotTagsNum'));
        $this->assign(['page'=>1,'showPages'=>[1],'pageNum'=>1,'topics'=>$topics,'user'=>session('user'),'hotTags'=>$hotTags]);
        echo $this->fetch('index');
    }
}
