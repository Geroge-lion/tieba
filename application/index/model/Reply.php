<?php 
namespace app\index\model;

class Reply extends \think\Model{
  public function user(){
   return	$this->belongsTo('User','user_id');
  }
}
