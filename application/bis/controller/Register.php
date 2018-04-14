<?php
namespace app\bis\controller;
use think\Controller;
class Register extends Controller
{
    public function index()
    {
        $city=model('City');//
        $citys=$city->getNormalCitysByParentid();
        $category=model('Category');
        $categorys=$category->getNormalFirstCategorys();
        return $this->fetch('',['citys'=>$citys,'categorys'=>$categorys]);
    }
    public function add()
    {
        //检验是否post方式发送数据
        if(!request()->isPost())
        {
            return $this->error('请求错误');
        }

        //获取表单的值
        $data=input('post.');

        //商户信息校验
        $validate_bis=validate('Bis');
        $validate_res=$validate_bis->scene('add')->check($data);
        if(!$validate_res)
        {
             $this->error($validate_bis->getError());
        }

        //门店信息校验
        $validate_bislocation=validate('BisLocation');
        if(!$validate_bislocation->scene('add')->check($data))
        {
            $this->error($validate_bislocation->getError());
        }

        //账户信息校验
        $validate_biscount=validate('Biscount');
        if(!$validate_biscount->scene('add')->check($data))
        {
            $this->error($validate_biscount->getError());
        }

        //地址信息校验
        $lnglat=\Map::getLngLat($data['address']);
        if(empty($lnglat)||$lnglat['status']!=0||$lnglat['result']['precise']!=1)
        {
            $this->error('地址信息不正确');
        }

        //商户基本信息入库
        $BisData=[
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'licence_image'=>$data['licence_logo'],
            'email'=>$data['email'],
            'description'=>empty($data['description'])?'':$data['description'],
            'bank_count'=>$data['bank_info'],
            'bank_name'=>$data['bank_name'],
            'bank_user'=>$data['bank_user'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?
                $data['city_id']:$data['city_id'].','.$data['se_city_id'],
        ];
        $bis_id=model('Bis')->add($BisData);//商品信息插入数据库

        //门店信息入库
        $data['cat']='';
        if(!empty($data['se_category_id']))
        {
            $data['cat']=implode('|',$data['se_category_id']);
        }
        $BisLocationData=[
            'name'=>$data['name'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?
                $data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'logo'=>$data['logo'],
            'description'=>empty($data['content'])?'':$data['content'],
            'contact'=>$data['contact'],
            'address'=>$data['address'],
            'tel'=>$data['tel'],
            'bis_id'=>$bis_id,
            'open_time'=>$data['open_time'],
            'category_id'=>$data['category_id'],
            'category_path'=>$data['category_id'].','.$data['cat'],
            'is_main'=>1,//代表总店信息
            'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
            'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
        ];

        $locationid=model('BisLocation')->add($BisLocationData);

        //账户信息入库
        //校验用户名是否存在
        $accouantVA=model('BisAccount')->where('username',$data['username'])->select();
        if($accouantVA)
        {
            $this->error('用户名已经存在，请重新选择用户名');
        }
        $data['code']=mt_rand(100,10000);//生成随机整数
        $bis_count_data=[
            'username'=>$data['username'],
            'password'=>md5($data['password'].$data['code']),
            'bis_id'=>$bis_id,
            'is_main'=>1,
            'code'=>$data['code'],

        ];
        $accountid=model('BisAccount')->add($bis_count_data);
        //验证是否申请成功
        if(empty($accountid))
        {
            $this->error('申请失败');
        }

        //发送邮件
        $url=request()->domain().url('bis/register/waiting',['bisid'=>$bis_id]);
        $emailTitle='o2o入驻';
        $emailContent="恭喜您，".$data['username'].",您的入驻申请已接收，正在审核中，欢迎您的到来！
            <a href='".$url."'>o2o团购网</a>".$url;
        $mailRes=\phpmailer\Email::send($data['email'],$emailTitle,$emailContent);
//        if(!$mailRes)
//        {
//            $this->error('邮件发送失败');
//        }

        return $this->success('入驻申请已提交',
            url('register/waiting',['bisid'=>$bis_id]));
    }
    public function waiting($bisid)
    {
       if(empty($bisid))
       {
           $this->error('bis_id error');
       }

       $detail=model('Bis')->where('id',$bisid)->find();
        $detail=$detail->getData();
        return $this->fetch('',['detail'=>$detail]);

    }
}