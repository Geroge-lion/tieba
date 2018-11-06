<?php 
namespace app\index\model;

class Topic_tag extends \think\Model{
   public static function getTopicTagsByTopicId($topicId){
   return self::where(['topic_id'=>$topicId])->select();
  }
   public function tag(){
   	return $this->belongsTo('Tag','tag_id');
   }
   public static function getHotTags($num){
	 $topicTag=new self();
	 return $topicTag->field(['tag_id','count(topic_id) as topicNum'])->group('tag_id')->limit($num)->select();
	}
}
