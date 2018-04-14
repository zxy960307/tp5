<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/12
 * Time: 17:23
 */
    namespace app\common\model;
    use think\Model;

    class Order extends Model
    {
        public function add($data)
        {
            $data['status']=1;//订单状态默认1
            $this->save($data);
            return $this->id;
        }
    }