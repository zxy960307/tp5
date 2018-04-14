<?php
namespace app\admin\controller;

use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
	public function welcome()
	{
		return "欢迎来到后台管理系统0！";
	}

    /**
     * @return mixed静态地图图片src
     */

	public function email()
    {
        \phpmailer\Email::send("414632302@qq.com","hello","hello world");
    }
    public function test()
    {
        return 'sinwa';
    }
}
