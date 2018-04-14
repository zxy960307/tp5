<?php
namespace app\bis\controller;
use think\Controller;
class Login extends Controller
{
    public function index()
    {
        //是否以post形式发送数据
        if(request()->isPost()) {
            $data=input('post.');//获取用户登陆数据

            //用户名校验
            $userRes=model('BisAccount')->get(['username'=>$data['username']]);
            if(!$userRes||$userRes->status!=1)
            {
                $this->error('该用户不存在，或者未审核通过');
            }
                //密码校验
            $md5Code=$userRes->code;//md5 code
            $passwordMd5=$userRes->password;//商家账户表md5加密密码
            $passwordInputMd5=md5($data['password'].$md5Code);//用户输入md5加密密码
            if($passwordInputMd5!=$passwordMd5)
            {
                $this->error('密码不正确');
            }

            model('BisAccount')->where('username',$userRes->username)
                ->update(['last_login_time'=>time()]);//更新上次登陆时间

            //保存用户信息,作用域为bis
            session('bisAccount',$userRes,'bis');
            return $this->success('登陆成功',url('bis\index'),2);
        }
        else{
            //获取session
            $account=session('bisAccount','','bis');//取值bis作用域
            if($account&&$account->id)
            {
               return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }
        public function logout()
        {
            session(null,'bis');//清除session
            $this->redirect(url('login/index'));//重定向至login页面
        }
}