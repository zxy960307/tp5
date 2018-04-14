<?php
namespace app\index\controller;

use think\Controller;
class User extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj=model('User');
    }

    //用户登陆页面
    public function login()
	{
	    $user=session('user','','o2o');
	    if($user)
        {
            $this->redirect('/');
        }
		return $this->fetch();
	}

	//用户注册页面
	public function register()
	{
	    if(!request()->isPost())
        {
            return $this->fetch();
        }
		else{
	        $data=input('post.');//获取注册数据

            //validata验证
            $validate=validate('User');
            if(!$validate->scene('register')->check($data))
            {
                $this->error($validate->getError());
            }

            //验证用户名是否已经存在
            if($this->obj->where('username',$data['username'])->find())
            {
                $this->error('用户名已经存在，请重新选择用户名');
            }

            //校验验证码
            if(!captcha_check($data['verifyCode']))
            {
                $this->error('验证码错误','',1);
            }

            //验证两次密码是否相同
            if($data['password']!=$data['repassword'])
            {
                $this->error('两次密码不相同，请重新输入');
            }

            //对密码进行md5加密
            $data['code']=mt_rand(100,10000);//生成随机整数
            $data['password']=md5($data['password'].$data['code']);//md5加密

            //数据进入数据库
            unset($data['repassword']);
            unset($data['verifyCode']);
            $res=$this->obj->add($data);
            if(!$res)
            {
                $this->error('会员注册失败');
            }
            return $this->success('会员注册成功',url('user/login'),3);

        }
	}

	//用户登陆数据校验
    public function loginCheck()
    {
        if(!request()->isPost())
        {
            $this->error('登陆数据提交不合法');
        }

        $data=input('post.');//获取数据

        //validate校验
        $validate=validate('User');
        if(!$validate->scene('login')->check($data))
        {
            $this->error($validate->getError());
        }

        $user=$this->obj->getUserInfoByName($data['username']);
        if(!$user)
        {
            $this->error('该用户不存在');
        }

        //判断密码是否正确
        if($user->password!=md5($data['password'].$user->code))
        {
            $this->error('密码不正确，请重新输入密码');
        }

        $this->obj->where('id',$user->id)->update(['last_login_time'=>time()]);

        //记录信息到session
        set_session_time(3600);
        session('user',$user,'o2o');

        $this->success('登陆成功',url('/'));
    }
    public function logout()
    {
        session(null,'o2o');//清除o2o作用域
        $this->redirect(url('user/login'));
    }
}
