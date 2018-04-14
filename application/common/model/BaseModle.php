<?php
namespace app\common\model;
use think\Model;

/**
 * Class Bis
 * 对商家信息进行数据库操作
 * @package app\common\model
 */
class BaseModle extends Model{

    protected $autoWriteTimestamp=true;
    public function add($data)
    {
        $data['status']=0;
        $this->save($data);
        return $this->id;
    }

    /**
     * 通过id查找记录
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInsById($id)
    {

        $res=$this->where('id',$id)->find();
        if(!$res)
        {
            return [];
        }
        return $res->getData();
    }
}