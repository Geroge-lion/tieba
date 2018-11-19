<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use app\index\model\User_info;
use think\Request;
class Index extends Controller
{   
    public function register(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		if(!captcha_check($postData['verifycode'])){
                return $this->error('验证码校验失败');
    		}
    		if(!$this->checkPassword($postData)){
    			return $this->error('密码校验失败');
    		}
    		$user=new User();
    		$user->name=$postData['username'];
    		$user->email=$postData['email'];
    		$user->avatar='images/avatar.jpg';
    		$user->password=md5(md5($postData['password']));
    		$user->created_at=time();
    		$user->save();
    		return $this->success('恭喜您，注册成功!现在去登录吧','/tieba/public/index.php/index/index/login');
    	}
            return $this->fetch();

    }
    public function checkPassword($data){
    	if(!$data['password']){
    		return false;
    	}
        if($data['password']===$data['password_confirmation']){
        	return true;
        }

        return false;
        
    }
    public function login(){
    	if(request()->isPost()){
    		$login=input('post.login');
    		$password=input('post.password');
            $cond=[];
            $cond['name|email']=$login;
            $cond['password']=md5(md5($password));
    		$user=User::get($cond);
    		if($user){
    			session('user',$user);
    			return $this->success('恭喜，登录成功!',"/tieba/public/index.php/index/topic/index");
                
    		}
    			return $this->success('抱歉，登陆失败！',"/tieba/public/index.php/index/index/login"); 
            	
    	}
        echo $this->fetch();
             
    }

    public function logout(){
    	session('user',null);
    	return $this->success('退出成功!',"/tieba/public/index.php/index/topic/index");
    }

    public function avatar(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        if(request()->isPost()){
            $file = request()->file('image');
            $res=uploadimg($file);
            if($res['isuploaded']){
                $uploadUser=User::get(['id'=>$user->id]);
                $uploadUser->avatar='upload'.DS.$res['savename'];
                $uploadUser->save();
                return $this->success('上传成功!',"/tieba/public/index.php/index/topic/index");
            }else{
                return $this->error($res['errormsg'],"/tieba/public/index.php/index/topic/index");
            }
        }
        return $this->fetch('',['user'=>$user]);
    }

    public function message(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        return $this->fetch('',['user'=>session('user')]);
    }

    public function my(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        if(request()->isPost()){
            $updates=input('post.');
            $user_info=User_info::get(['user_id'=>$user->id]);
            if($user_info){
                $user_info->user_id=$user->id;
                $user_info->nickname=$updates['nickname']?:'';
                $user_info->location=$updates['location']?:'';
                $user_info->company=$updates['company']?:'';
                $user_info->website=$updates['website']?:'';
                $user_info->signature=$updates['signature']?:'';
                $user_info->intro=$updates['bio']?:'';
                $user_info->language=$updates['locale']?:3;
                $user_info->created_at=time();
                $res=$user_info->save();
                if($res){
                    return $this->success('恭喜，更新成功!',"/tieba/public/index.php/index/index/my");
                }else{
                    return $this->error('抱歉，更新失败！',"/tieba/public/index.php/index/index/my"); 
                }      
            }else{
                $user_info=new User_info;
                $user_info->user_id=$user->id;
                $user_info->nickname=$updates['nickname']?:'';
                $user_info->location=$updates['location']?:'';
                $user_info->company=$updates['company']?:'';
                $user_info->website=$updates['website']?:'';
                $user_info->signature=$updates['signature']?:'';
                $user_info->intro=$updates['bio']?:'';
                $user_info->language=$updates['locale']?:3;
                $user_info->created_at=time();
                $res=$user_info->save();
                if($res){
                    return $this->success('恭喜，更新成功!',"/tieba/public/index.php/index/index/my");
                }else{
                    return $this->error('抱歉，更新失败！',"/tieba/public/index.php/index/index/my"); 
                }      
            }
        }
        return $this->fetch('',['user'=>$user]);
    }

    public function password(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        if(request()->isPost()){
            $data=input('post.');
            if($data['password']!=$data['password_confirmation']){
                return $this->error('两次密码输入不一致！',"/tieba/public/index.php/index/index/password");
            }
            $new_password=md5(md5($data['password']));
            if(md5(md5($data['old_password']))!=$user->password){
                return $this->error('密码输入错误！',"/tieba/public/index.php/index/index/password");
            }
            $update_user=User::get(['id'=>$user->id]);
            $update_user->password=$new_password;
            $res=$update_user->save();
            if($res){
                session('user',null);
                return $this->success('恭喜,更新成功,请重新登录!',"/tieba/public/index.php/index/index/login");
            }else{
                return $this->error('更新失败，请联系管理员!',"/tieba/public/index.php/index/index/my");
            }
        }
        return $this->fetch('',['user'=>session('user')]);
    }
    public function post(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        return $this->fetch('',['user'=>session('user')]);
    }
    public function score(){
        $user=session('user');
        if(!$user){
            return $this->fetch('index/login');
        }
        return $this->fetch('',['user'=>session('user')]);
    }
    public function userinfo(){
        $id=intval(input('get.id'));
        if(!$id){
            return $this->error('您查看的用户不存在','/tieba/public/index.php/index/topic/index');
        }
        $user_info=User_info::get(['user_id'=>$id]);
        if(!$user_info){
            return $this->error('您查看的用户未填写个人信息','/tieba/public/index.php/index/topic/index');
        }
        $user_view=User::get(['id'=>$id]);
        if(!$user_view){
            return $this->error('您查看的用户不存在','/tieba/public/index.php/index/topic/index');
        }
        $this->assign(['user'=>session('user'),'user_info'=>$user_info,'user_view'=>$user_view]);
        return $this->fetch('');
    }

    public function about(){
        return $this->fetch('',['user'=>session('user')]);
    }

}
