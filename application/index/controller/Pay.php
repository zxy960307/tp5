<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/12
 * Time: 17:33
 */
    namespace app\index\controller;
    use think\Controller;

    class Pay extends Base
    {
        public function index()
        {
            //判断用户是否登陆
            if(!$this->isLogin())
            {
                $this->error("您当前未登陆，请登陆",url("user/login"));
            }

            //获取订单信息
            $order_id=input('get.id',0,"intval");
            if(!$order_id)
            {
                $this->error("订单信息错误");
            }
            $order=model("Order")->where('id',$order_id)->find();
            if(!$order)
            {
                $this->error("订单信息错误");
            }

            //导入文件
            $config=config("alipay.");
            import("alipay\pagepay\service\AlipayTradeService",EXTEND_PATH);
            import("alipay\pagepay\buildmodel\AlipayTradePagePayContentBuilder",EXTEND_PATH);

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no =$order->out_trade_no;

            //订单名称，必填
            $subject = "o2o 支付";

            //付款金额，必填
            $total_amount =$order->total_price;

            //商品描述，可空
            $body ="";

            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);

            $aop = new AlipayTradeService($config);

            /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
             */
            $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            //输出表单
            var_dump($response);
        }
    }