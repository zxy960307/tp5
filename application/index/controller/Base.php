<?php
    namespace app\index\controller;
    use think\Controller;

    class Base extends Controller
    {
        public $city='';
        public $user;

        public function _initialize()
        {
            //城市数据
            $citys=model('City')->getNormalCitys();
            $this->getDefaultCity($citys);

            //分类数据
            $cats=$this->getRecommendCats();

            $this->assign('citys',$citys);
            $this->assign('city',$this->city);
            $this->assign('user',$this->getLoginUser());//用户数据
            $this->assign('cats',$cats);
            $this->assign('controller',strtolower(request()->controller()));
            $this->assign('title','o2o团购网');
        }

        /**
         *  获取用户选择城市
         * @param $citys 所有城市
         */
        public function getDefaultCity($citys)
        {
            $cityuname='';
            foreach ($citys as $city)
            {
                $city=$city->toArray();
                if($city['is_default']==1)
                {
                    $cityuname=$city['uname'];
                    break;
                }
            }
            $cityuname=$cityuname?$cityuname:'wendeng';

            if(session('cityuname','','o2o')&&!input('get.city'))
            {
                $cityuname=session('cityuname','','o2o');
            }
            else{
                $cityuname=input('get.city',$cityuname,'trim');
                session('cityuname',$cityuname,'o2o');
            }
            $this->city=model('City')->where('uname',$cityuname)->find();
        }

        /**
         *  获取用户登陆信息
         * @return mixed 用户登陆信息
         */
        public function getLoginUser()
        {
            if(!$this->user)
            {
                $this->user=session('user','','o2o');
            }
            return $this->user;
        }

        /**
         *  判断用户是否登陆
         */
        public function isLogin()
        {
            $user=$this->user;
            if($user&&$user->id)
            {
                return true;
            }
            else{
                return false;
            }
        }
        /**
         * 获取首页推荐中分类数据
         */
        public function getRecommendCats()
        {
            //获取一级分类数据
            $parentIds=$sedCatAttr=$reCats=[];
            $cats=model('Category')->getRecommendCategorysByParentId(0,5);
            foreach ($cats as $cat)
            {
                $parentIds[]=$cat->id;
            }

            //获取二级分类数据
            $seCats=model('Category')->getNormalRecommendCategorysById($parentIds);
            foreach ($seCats as $seCat) {
                $sedCatAttr[$seCat->parent_id][]=[
                    'id'=>$seCat->id,
                    'name'=>$seCat->name,
                ];
            }
            foreach ($cats as $cat) {
                $reCats[$cat->id]=[
                    $cat->name,
                    empty($sedCatAttr[$cat->id])?[]:$sedCatAttr[$cat->id],
                    ];
            }
            //返回所有一级和二级分类数据
            return $reCats;
        }
    }