<?php
    namespace app\admin\controller;
    use think\Controller;

    class Bis extends Controller
    {
        private $obj;//model对象

        public function _initialize()
        {
            $this->obj = model('Bis');
        }

        public function index()
        {
            $bis = $this->obj->getBisByStatus(1);
            return $this->fetch('', ['bis' => $bis]);
        }

        /**
         * 入驻申请列表
         * @return mixed
         */
        public function apply()
        {
            $bis = $this->obj->getBisByStatus();
            return $this->fetch('', ['bis' => $bis]);
        }

        public function dellist()
        {
            $bis = $this->obj->getBisByStatus(2);
            return $this->fetch('', ['bis' => $bis]);
        }

        /**
         * 详情页
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function detail()
        {
            //商户id
            $id = input('get.id');
            if (!$id) {
                $this->error('id不存在');
            }

            //获得一级城市和分类id
            $city = model('City');//
            $citys = $city->getNormalCitysByParentid();
            $category = model('Category');
            $categorys = $category->getNormalFirstCategorys();

            //返回商户、门店、账户数据
            $bisData = $this->obj->getInsById($id);
            $locationData = model('BisLocation')
                ->where('bis_id', $id)
                ->where('is_main', 1)
                ->find();
            $accountData = model('BisAccount')
                ->where('bis_id', $id)
                ->where('is_main', 1)
                ->find()
                ->getData();

            //通过路径获得二级分类名数组
            $secategoryid = getSeCategoryId($locationData['category_path']);
            $secategoryname = [];
            foreach ($secategoryid as $value) {
                $secategoryname[] = getCategoryName($value);
            }

            return $this->fetch('', ['citys' => $citys,
                'categorys' => $categorys,
                'bisData' => $bisData,
                'accountData' => $accountData,
                'locationData' => $locationData,
                'secategoryname' => $secategoryname,
            ]);
        }

        public function status()
        {
            //获得id status
            $bisId = input('get.id');
            $status = input('get.status');

            //修改数据表中status值
            $bisRes = model('Bis')->where('id', $bisId)->Update(['status' => $status]);
            $locationRes = model('BisLocation')->where('bis_id', $bisId)
                ->where('is_main', 1)
                ->Update(['status' => $status]);
            $accountRes = model('BisAccount')->where('bis_id', $bisId)
                ->where('is_main', 1)
                ->Update(['status' => $status]);

            //修改该商户分店status
            if($status==-1)
            {
                model('BisLocation')
                    ->where('bis_id',$bisId)
                    ->where('is_main',0)
                    ->update('status',-1);
            }
            elseif($status==2)
            {
                model('BisLocation')
                    ->where('bis_id',$bisId)
                    ->where('is_main',0)
                    ->where('status','NEQ', -1)
                    ->update('status',2);
            }

            if ($bisRes && $locationRes && $accountRes) {
                //发送邮件
                $data = model('Bis')->get($bisId)->getData();
                $url = request()->domain() . url('bis/register/waiting', ['bisid' => $bisId]);
                $emailTitle = 'o2o入驻申请审核情况';
                $emailContent = "恭喜您，您的入驻申请已被处理，请查看审核状态:
            <a href='" . $url . "'>o2o团购网</a>";
                $mailRes = \phpmailer\Email::send($data['email'], $emailTitle, $emailContent);

                $this->success('状态更新成功');
            } else {
                $this->error('状态更新失败');
            }
        }
//分店申请页面
        public function locationapply()
        {
            //分店信息
            $location = model('BisLocation')->where('is_main', 0)
                ->where('status', 0)->paginate(5);

            //返回该分店的商户名称
            foreach ($location as $key => $value) {
                $value['bis_name'] = $this->obj->get(['id' => $value['bis_id']])->name;
            }
            return $this->fetch('', ['location' => $location]);
        }

        public function locationStatus()
        {
            //获得id status
            $id = input('get.id');
            $status = input('get.status');

            //修改门店表中状态值
            $location = model('BisLocation')->where('id', $id)
                ->where('is_main', 0)->find();
            $locationRes = model('BisLocation')->where('id', $id)
                ->where('is_main', 0)
                ->Update(['status' => $status]);
            if ($locationRes) {
                //发送邮件
                $data = model('bis')->where('id', $location['bis_id'])->find();
                $emailTitle = 'o2o入驻申请审核情况';
                $emailContent = "恭喜您，您的分店申请已被处理，请登陆后台查看审核状态!";
                $mailRes = \phpmailer\Email::send($data['email'], $emailTitle, $emailContent);

                $this->success('分店状态更新成功');
            }
            $this->error('分店状态更新失败');
        }
        public function locationdetail()
        {
            //商户id
            $locationId = input('get.id');
            if (!$locationId) {
                $this->error('id不存在');
            }

            //获得一级城市和分类id
            $city = model('City');//
            $citys = $city->getNormalCitysByParentid();
            $category = model('Category');
            $categorys = $category->getNormalFirstCategorys();

            //返回商户、门店、账户数据
            $bis_id=model('BisLocation')->where('id', $locationId)->find()['bis_id'];
            $bis_name=$this->obj->where('id',$bis_id)->find()['name'];
            $locationData = model('BisLocation')
                ->where('id', $locationId)
                ->find();

            //通过路径获得二级分类名数组
            $secategoryid = getSeCategoryId($locationData['category_path']);
            $secategoryname = [];
            foreach ($secategoryid as $value) {
                $secategoryname[] = getCategoryName($value);
            }

            return $this->fetch('', ['citys' => $citys,
                'categorys' => $categorys,
                'locationData' => $locationData,
                'secategoryname' => $secategoryname,
                'bisname'=>$bis_name,
            ]);
        }
        public function location()
        {
            $location=model('BisLocation')->where('status',1)
                ->where('is_main',0)->paginate(5);
            //返回该分店的商户名称
            foreach ($location as $key => $value) {
                $value['bis_name'] = $this->obj->get(['id' => $value['bis_id']])->name;
            }
            return $this->fetch('',['location'=>$location]);
        }

    }