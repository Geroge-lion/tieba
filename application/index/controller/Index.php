<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
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
    		$user->create_at=time();
    		$user->save();
    		return $this->success('恭喜您，注册成功!','/tieba/public/index.php/index/index/login');
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

}
