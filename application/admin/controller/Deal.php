<?php
    namespace app\admin\controller;
    use think\Controller;

    class Deal extends Controller
    {
        private $obj;

        public function _initialize()
        {
            $this->obj=model('Deal');
        }

         //团购商品列表页
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
            $deals=$this->obj->getNormalDeals($sdata);

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

        //团购商品审核页
        public function apply()
        {
            return $this->fetch();
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

            $deal=$this->obj->where('id',$dealId)->find()->getData();
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
    }