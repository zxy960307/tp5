<?php
    namespace app\common\model;
    use think\Model;

    /**
     * Class Bis
     * 对商家信息进行数据库操作
     * @package app\common\model
     */
    class Bis extends BaseModle {
        public function getBisByStatus($status=0)
        {
            $result=$this->where('status',$status)->order('id desc')->paginate(5);
            if($result)
            {
                return $result;
            }
            else
            {
                return [];
            }
        }


    }