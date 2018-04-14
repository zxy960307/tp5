<?php
    namespace app\common\model;
    use think\Model;

    class User extends BaseModle
    {
        /**
         *  将用户数据插入数据库
         * @param $data
         * @return mixed
         */
        public function add($data)
        {
            $data['status']=1;
            $this->save($data);
            return $this->id;
        }

        public function getUserInfoByName($name)
        {
            $res=$this->where('username',$name)
                ->where('status',1)
                ->find();
            return $res;
        }

        public function updateById($data,$id)
        {
            $res=$this->where('id',$id)->update($data);
            return $res;
        }
    }