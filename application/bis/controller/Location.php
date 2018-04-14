<?php
    namespace app\bis\controller;
    use think\Controller;
    class Location extends Base{
        //门店列表页
        public function index()
        {
            $location=model('BisLocation')
                ->where('bis_id',$this->account->bis_id)->select();
            return $this->fetch('',['location'=>$location]);
        }
        //添加分店页
        public function add()
        {
            //若不是post提交
            if(!request()->isPost())
            {
                $city=model('City');//
                $citys=$city->getNormalCitysByParentid();
                $category=model('Category');
                $categorys=$category->getNormalFirstCategorys();
                return $this->fetch('',['citys'=>$citys,'categorys'=>$categorys]);
            }
            //post提交时
            else {
                $data=input('post.');

                //门店信息校验
                $validate_bislocation=validate('BisLocation');
                if(!$validate_bislocation->scene('add')->check($data))
                {
                    $this->error($validate_bislocation->getError());
                }

                //地址信息校验
                $lnglat=\Map::getLngLat($data['address']);
                if(empty($lnglat)||$lnglat['status']!=0||$lnglat['result']['precise']!=1)
                {
                    $this->error('地址信息不正确');
                }

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
                    'bis_id'=>$this->account->bis_id,
                    'open_time'=>$data['open_time'],
                    'category_id'=>$data['category_id'],
                    'category_path'=>$data['category_id'].','.$data['cat'],
                    'is_main'=>0,//代表分店信息
                    'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
                    'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
                ];
                $locationid=model('BisLocation')->add($BisLocationData);
                if(!$locationid)
                {
                    return $this->error('申请门店失败');
                }
                return $this->success('申请门店成功');
            }
        }
        public function locationdetail()
        {
            $id=input('get.id');
            if(!$id)
            {
                $this->error('id不存在');
            }

            //门店信息
            $data=model('BisLocation')->where('id',$id)->find();

            //获得一级城市和分类id
            $city = model('City');//
            $citys = $city->getNormalCitysByParentid();
            $category = model('Category');
            $categorys = $category->getNormalFirstCategorys();

            //通过路径获得二级分类名数组
            $secategoryid = getSeCategoryId($data['category_path']);
            $secategoryname = [];
            foreach ($secategoryid as $value) {
                $secategoryname[] = getCategoryName($value);
            }
            return $this->fetch('',[
                'categorys' => $categorys,
                'locationData' => $data,
                'secategoryname' => $secategoryname,
                    ]
                );

        }
        public function locationstatus()
        {
            $id = input('get.id');
            $status = input('get.status');

            //修改门店表中状态值
            $locationRes = model('BisLocation')->where('id', $id)
                ->where('is_main', 0)
                ->Update(['status' => $status]);
            if($locationRes)
            {
                $this->success('分店下架成功');
            }
            return $this->error('门店下架失败');
        }
    }