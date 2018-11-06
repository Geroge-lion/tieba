<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Reply as ReplyModel;
class Reply extends Controller
{   
public function newReply(){
    $postData=input('post.');
    $reply=new ReplyModel();
    $user=session('user');
    $reply->content=$postData['content'];
    $reply->topic_id=$postData['topic_id'];
    $reply->user_id=$user->id;
    $reply->create_at=time();
    $reply->reply_id=$postData['reply_id']>0?$postData['reply_id']:0;
    $reply->save();
    $this->success('恭喜您，回复成功！');
}
}
