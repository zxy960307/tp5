<?php
    namespace app\bis\controller;
    use think\Controller;

    class Deal extends Base
    {
        /**
         * 团购商品列表页
         */
        public function index()
        {
            $data=input('get.');
            $sdata=[];
            //查询创建于开始和结束时间的团购商品,由于create_time存在索引，故放在第一位
            if(!empty($data['start_time'])&&empty($data['end_time'])&&
                (strtotime($data['end_time'])-strtotime($data['start_time'])>0))
            {
                $sdata['create_time']=
                    [
                        ['gt',strtotime($data['end_time'])],
                        ['lt',strtotime($data['start_time'])],
                    ];
            }
            //相关分类
            elseif(!empty($data['category_id']))
            {
                $sdata['category_id']=$data['category_id'];
            }
            //相关城市
            elseif(!empty($data['city_id']))
            {
                $sdata['city_id']=$data['city_id'];
            }
            //模糊查询团购商品名
            elseif(!empty($data['name']))
            {
                $sdata['name']=['like','%'.$data['name'].'%'];
            }


            //获得一级分类和二级城市
            $city = model('City');//
            $citys = $city->getNormalCitys();
            $category = model('Category');
            $categorys = $category->getNormalFirstCategorys();

            //获取符合条件的团购商品
            $deals=model('deal')
                ->where($sdata)
                ->where('bis_id',$this->account->bis_id)
                ->order('id','desc')
                ->paginate(5);

            return $this->fetch('',[
                'categorys'=>$categorys,
                'citys'=>$citys,
                'deals'=>$deals,
                'category_id'=>empty($data['category_id'])?'':$data['category_id'],
                'city_id'=>empty($data['city_id'])?'':$data['city_id'],
                'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
                'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
                'name' => empty($data['name']) ? '' : $data['name'],
            ]);
        }
        /**
         * 团购商品添加页面
         * @return mixed
         */
        public function add()
        {
            $bisId=$this->account->bis_id;

            //非post提交，引入模板
            if(!request()->isPost())
            {

                //获得一级城市和分类id
                $city = model('City');//
                $citys = $city->getNormalCitysByParentid();
                $category = model('Category');
                $categorys = $category->getNormalFirstCategorys();

                //

                $bisLocations=model('BisLocation')
                    ->where('bis_id',$bisId)
                    ->where('status',1)
                    ->select();

                return $this->fetch('',[
                    'citys'=>$citys,
                    'categorys'=>$categorys,
                    'bislocations'=>$bisLocations,
                ]);
            }
            //post提交，插入shuju
            else{
                $data=input('post.');

                //验证数据格式
                $validate=validate('Deal');
                if(!$validate->scene('add')->check($data))
                {
                    $this->error($validate->getError());
                }
                //验证日期格式
                $start_time=strtotime($data['start_time']);
                $end_time=strtotime($data['end_time']);
                $coupons_begin_time= strtotime($data['coupons_begin_time']);
                $coupons_end_time=strtotime($data['coupons_end_time']);
                if($start_time>=$end_time)
                {
                    $this->error('团购开始时间不得超过或等于结束时间');
                }
                elseif($coupons_begin_time>=$coupons_end_time)
                {
                    $this->error('消费券生效时间不得超过或等于结束时间');
                }
                elseif($coupons_begin_time<=$start_time)
                {
                    $this->error('团购开始时间不得超过消费券生效时间');
                }

                //信息入库
                //获取第一个门店位置信息
                $locaiton=model('BisLocation')->where('id',$data['location_ids'][0])
                    ->find();
                $deals=[
                    'name'=>$data['name'],
                    'bis_id'=>$bisId,
                    'bis_account_id'=>$this->account->id,
                    'location_id'=>implode(',',$data['location_ids']),
                    'image'=>$data['image'],
                    'description'=>empty($data['description'])?
                                    '':$data['description'],
                    'notes'=>empty($data['notes'])?
                        '':$data['notes'],
                    'start_time'=>$data['start_time'],
                    'end_time'=>$data['coupons_end_time'],
                    'coupons_start_time'=>$data['coupons_begin_time'],
                    'coupons_end_time'=>$data['coupons_end_time'],
                    'total_count'=>$data['total_count'],
                    'category_id'=>$data['category_id'],
                    'se_category_id'=>empty($data['se_category_id'])?
                        '':implode(',',$data['se_category_id']),
                    'city_id'=>$data['city_id'],
                    'origin_price'=>$data['origin_price'],
                    'current_price'=>$data['current_price'],
                    'xpoint'=>$locaiton['xpoint'],
                    'ypoint'=>$locaiton['ypoint'],
                    'se_city_id'=>empty($data['se_city_id'])?'':$data['se_city_id'],
                ];
                $res=model('Deal')->add($deals);
                if(!$res)
                {
                    $this->error('团购商品信息进入数据库失败');
                }
                else{
                    $this->error('团购商品信息提交成功',url('deal/index'));
                }
            }

        }
        public function status()
        {
            $status=input('get.status');
            $id=input('get.id');
            $res=model('Deal')->where('id',$id)->setField('status',$status);
            if($res)
            {
                $this->success('团购商品状态更新成功');
            }
            return $this->error('团购商品状态更新失败');
        }
        //团购商品详情页
        public function dealDetail()
        {
            //获取商品id
            $dealId=input('get.id');
            if(!$dealId)
            {
                $this->error('团购商品id不存在');
            }

            $deal=model('Deal')->where('id',$dealId)->find()->getData();
            $secategoryids=explode(',',$deal['se_category_id']);
            $secategoryname=[];
            foreach ($secategoryids as $value)
            {
                $secategoryname[]=model('Category')->where('id',$value)->find()['name'];
            }
            $bislocations=[];
            $locationid=explode(',',$deal['location_id']);
            foreach ($locationid as $value)
            {
                $bislocations[]=model('BisLocation')->where('id',$value)->find()['name'];
            }
            return $this->fetch('',[
                'deal'=>$deal,
                'secategoryname'=>$secategoryname,
                'bislocations'=>$bislocations,
            ]);

        }
    }