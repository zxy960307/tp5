<?php
    namespace app\bis\controller;
    use think\Controller;

    class Base extends Controller
    {
        public $account;
        public function _initialize()
        {
            $loginFlag=$this->isLogin();
            if(!$loginFlag)
            {
                $this->redirect(url('login/index'));
            }
        }
        //判断商户是否登陆
        public function isLogin()
        {
            $user=$this->getLoginUser();
            if($user&&$user->id)
            {
                return true;
            }
            return false;
        }
        //获得用户信息
        public function getLoginUser()
        {
            if(!$this->account)
            {
                $this->account=session('bisAccount','','bis');//获取session
            }
            return $this->account;
        }
    }