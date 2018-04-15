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
            $config=config("alipay");
            import("alipay/pagepay/service/AlipayTradeService",EXTEND_PATH);
            import("alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder",EXTEND_PATH);

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no =$order->out_trade_no;

            //订单名称，必填
            $deal_name=model("Deal")->where("id",$order->deal_id)->find()->name;
            $subject = "o2o订单 商品名:"
                .$deal_name."*".$order->deal_count." 订单号:".$order->out_trade_no;

            //付款金额，必填
            $total_amount =$order->total_price;

            //商品描述，可空
            $body ="";

            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);

            $aop = new \AlipayTradeService($config);

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

	public function paysuccess()
	{
	    return $this->fetch();
	}

        //同步回跳页面
        public function alipayReturn()
        {
            //导入文件
            $config=config("alipay");
            import("alipay/pagepay/service/AlipayTradeService",EXTEND_PATH);

            //获取支付宝post信息
            $arr=$_GET;
            $alipaySevice = new \AlipayTradeService($config);
            $result = $alipaySevice->check($arr);//验签支付宝返回的信息，使用支付宝公钥
            if($result)
            {
                $out_trade_no=$arr['out_trade_no'];
                $order=model('Order')->where('out_trade_no',$out_trade_no)
                    ->find();
                $deal=model('Deal')->where('id',$order->deal_id)->find();
                return $this->fetch('',[
                    'deal'=>$deal,
                    'order'=>$order,
                ]);
            }
            else{
                $this->error("订单处理失败");
            }
        }

        //异步回跳页面
        public function notify()
        {
            //导入文件
            $config=config("alipay");
            import("alipay/pagepay/service/AlipayTradeService",EXTEND_PATH);

            //获取支付宝post信息
            $arr=$_POST;
		$postStr="";
            foreach ($arr as $key=>$item) {
                $postStr.=$key."=>".$item.",";
            }

            $fh=fopen(dirname ( __FILE__ ).DIRECTORY_SEPARATOR."./../../post_info.txt","a");
            fwrite($fh,$postStr);
		fclose($fh);
            $alipaySevice = new \AlipayTradeService($config);
            $result = $alipaySevice->check($arr);
            if($result) {//验证成功

                //商户订单号
                $out_trade_no = $_POST['out_trade_no'];

                //支付宝交易号
                $trade_no = $_POST['trade_no'];

                //交易状态
                $trade_status = $_POST['trade_status'];
		//model("Order")->where("out_trade_no",$out_trade_no)->update(["pay_time"=>$trade_status]);
                //交易已结束
                if($_POST['trade_status'] == 'TRADE_FINISHED') {
                    echo "success";
                }
                //未付款交易超时关闭
                elseif($_POST['trade_status'] == 'TRADE_CLOSED')
                {
                    echo "success";
                }
                //交易创建
                else if($_POST['trade_status'] == "WAIT_BUYER_PAY")
		        {
		            echo "fail";
		        }

                //查看订单号是否存在
                $order=model('Order')->where('out_trade_no',$out_trade_no)->find();
                if(empty($order))
                {
                    //验证失败
                    echo "success";
                }

                //查看交易状态
                if($order->pay_status!=0)
                {
                    //验证失败
                    echo "success";
                }

                //入库操作
                $data=[
                    'trade_no'=>$trade_no,
                    'pay_time'=>time(),
                    'pay_status'=>1,
                    'pay_amount'=>$_POST['total_amount'],
                ];
                $res=model('Order')->where('out_trade_no',$out_trade_no)
                    ->update($data);
                if(!$res)
                {
                    echo "fail";
                }

                //将团购商品总数做相应处理
                $res=model('Deal')->increaseCount($order->deal_id,$order->deal_count);
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                echo "success";	//请不要修改或删除
            }else {
                //验证失败
                echo "fail";
           }
}
    }

