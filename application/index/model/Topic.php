<?php 
namespace app\index\model;

class Topic extends \think\Model

{
	public static function getTopic($id){
      return self::withCount(['praises','replies'])->find(['id'=>$id]);
	}
	
	public function user(){
      return $this->belongsTo('User','user_id');
	}
	
	 public function praises(){
      return $this->hasMany('Praise','topic_id');
	}
	public function replies(){
      return $this->hasMany('reply','topic_id');
	}

	public static function getTopics($page){
		$start=($page-1)*config('limitNum');
		return self::withCount(['praises','replies'])->limit($start,2)->select();
	}
	public static function getPageInfo($page){
       $page=intval($page)<1?1:intval($page);
       $count=self::count();
       $pageNum=ceil($count/config('limitNum'));
       $showPages=[];
       for($leftPage=$page-3;$leftPage<=$page;$leftPage++){
       	if($leftPage>0){$showPages[]=$leftPage;}
       	
       }
       for($i=1;$i<=3;$i++){
       	if($page+$i<=$pageNum){
       		$showPages[]=$page+$i;
       	}
       }
       return ['page'=>$page,'showPages'=>$showPages,'pageNum'=>$pageNum];
	}

	public static function search($keyword){
     $con=[];
     $con['title']=['like','%'.$keyword.'%'];
     return self::withCount(['praises','replies'])->where($con)->select();
	}

	public static function getTagTopics($topicIds){
		$con=[];
		$con['id']=['in',$topicIds];
	 return	self::withCount(['praises','replies'])->where($con)->select();
	}
	}
