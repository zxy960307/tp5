<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/15
 * Time: 17:05
 */
    namespace app\api\controller;
    use think\Controller;

class Order extends Controller{

    private $obj;

    public function _initialize()
    {
        $this->obj=model('Order');
    }
    public function getStatus()
    {
        $id=input('post.id',0,'intval');
        if(!$id)
        {
            return show(0,'error');
        }

        //判断是否登陆

        //获取订单状态
        $order=$this->obj->where('id',$id)->find();
        if($order->pay_status==1)
        {
            return show(1,'success');
        }
        return show(0,'error');

    }
}