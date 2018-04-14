<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\File;
class Image extends Controller
{
    public function upload()
    {
        $file=Request::instance()->file('file');//instance() 实例化一个request对象并调用静态方法
        $info=$file->move('upload');
        if($info&&$info->getPathname())
        {
            return show('1','success',['src'=>'/'.$info->getPathname()]);
        }
        else{
            return show('0','error','');
        }
    }
}