<?php
namespace app\common\model;

use think\Model;

class City extends Model
{
    /**
     * 通过父级id获得城市表中相关记录
     * @param int $parent_id 父级id，默认为0
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCitysByParentid($parent_id=0)
    {
        $order=['id'=>'desc'];
        $citys=$this->where('parent_id',$parent_id)
            ->where('status','1')
            ->order($order)
            ->select();
        return $citys;
    }

    /**
     * 得到城市表中所有二级城市记录
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCitys()
    {
        $normalCitys=$this->where('status',1)
            ->where('parent_id','NEQ','0')
            ->select();
        return $normalCitys;
    }
}