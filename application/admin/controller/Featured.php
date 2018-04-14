<?php
    namespace app\admin\controller;
    use think\Controller;

    class Featured extends Base
    {
        private $obj;

        public function _initialize()
        {
            $this->obj=model('Featured');
        }

        //推荐位首页
        public function index()
        {
            //获取推荐位分类
            $types=config('featured.featured_type');

            //获取类别
            $type=input('get.type',0,'intval');
            $results=$this->obj->getFeaturedsByType($type);
            return $this->fetch('',[
                'types'=>$types,
                'results'=>$results,
                'type'=>empty($type)?-1:$type,
            ]);
        }

        //后台添加推荐位页
        public function add()
        {
            if(request()->isPost())
            {
                //获取数据
                $data=input('post.');

                //验证数据
                $validate=validate('Featured');
                if(!$validate->check($data))
                {
                    $this->error($validate->getError());
                }

                //数据进入数据库,返回数据表id
                $id=$this->obj->add($data);
                //判断id是否存在
                if($id)
                {
                    $this->success('推荐位添加成功');
                }
                else{
                    $this->error('推荐位添加失败');
                }

            }
            else{
                //推荐位列表
                $types=config('featured.featured_type');

                return $this->fetch('',[
                    'types'=>$types,
                ]);
            }
        }
    }