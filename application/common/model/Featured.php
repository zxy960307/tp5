<?php
    namespace app\common\model;
    use think\Controller;

    class Featured extends BaseModle
    {
        /**
         * 通过类别获得status不等于-1的推荐位数据
         * @param $type 类别
         * @return \think\Paginator 推荐位信息
         */
        public function getFeaturedsByType($type)
        {
            $res=$this->where('type',$type)
                ->where('status','NEQ','-1')
                ->order('id','desc')
                ->paginate(5);
            return $res;
        }

        /**
         *  通过类别名获得status为1的推荐位信息
         * @param $type 类别
         * @return \think\Paginator 推荐位信息
         */
        public function getNormalFeaturedsByType($type)
        {
            $res=$this->where('type',$type)
                ->where('status',1)
                ->order('id','desc')
                ->paginate(5);
            return $res;
        }
    }