<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/12
 * Time: 9:48
 * 订单类
 */

    namespace app\index\controller;
    use think\Controller;

    class Order extends Base
    {
        //订单确认页面
        public function index()
        {
            //判断用户是否登陆
            if(!$this->isLogin())
            {
                $this->error('您当前未登陆，请登陆',url('user/login'));
            }

            //获得表单数据
            $id=intval(input('get.id',0));//商品id
            if(!$id)
            {
                $this->error('参数不合法');
            }
            $count=intval(input('get.count',1));//购买数目

            $deal=model('Deal')->get($id);//根据id获取商品
            $deal=$deal->toArray();//转化为数组
            return $this->fetch('',[
                'controller'=>'pay',
                'deal'=>$deal,
                'count'=>$count,
                ]);
        }

        //订单入库
        public function add()
        {
            //判断用户是否登陆
            if(!$this->isLogin())
            {
                $this->error('您当前未登陆，请登陆',url('index/login'));
            }

            //获取表单信息
            $deal_id=input('get.id',0,"intval");
            $count=input('get.count',0,"intval");
            $total_price=input('get.total_price',0,"intval");
            if(!($deal_id||$count||$total_price))
            {
                $this->error('参数错误，返回首页',url('/'));
            }

            //获取商品信息
            $deal=model('Deal')->where('status',1)->where('id',$deal_id)->find();
            if(!$deal)
            {
                $this->error('商品不存在，返回首页',url('/'));
            }

            //防止外链接
            if(empty($_SERVER['HTTP_REFERER']))
            {
                $this->error('请求不合法，返回首页',url('/'));
            }

            //判断是否有库存
            if($deal->total_count<$count)
            {
                $this->error('库存不充足，请选择其他商品',url('/'));
            }

            //入库
            $orderSn=setOrderSn();
            $data=[
                'out_trade_no'=>$orderSn,
                'user_id'=>$this->user->id,
                'username'=>$this->user->username,
                'deal_id'=>$deal_id,
                'deal_count'=>$count,
                'total_price'=>$total_price,
                'referer'=>$_SERVER['HTTP_REFERER'],
            ];
            try{
                $orderId=model('Order')->add($data);
            }
            catch (\Exception $e)
            {
                $this->error('订单处理失败',url('/'));
            }
            return $this->redirect(url('pay/index',['id'=>$orderId]));
        }
    }