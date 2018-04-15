<?php
    namespace app\common\model;
    use think\model;

    class Deal extends BaseModle
    {
        //获得所有团购商品
        public function getAllDeals()
        {
            $allDeals=$this->select();
            return $allDeals;
        }
        //通过条件获得deal表中记录
        public function getNormalDeals($data=[])
        {
            $data['status']=1;
            $res=$this->where($data)->order('id','desc')->paginate(5);
            return $res;
        }

        /**
         * 通过给定分类id\城市id\记录条数获得商品信息
         * @param $categoryId 分类id
         * @param $cityId 城市id
         * @param int $limit    限制记录数
         * @return false|\PDOStatement|string|\think\Collection 商品信息
         */
        public function getDealsByCategoryCity($categoryId,$cityId,$limit=10)
        {
            $data=[
                'category_id'=>$categoryId,
                'city_id'=>$cityId,
                'status'=>1
            ];
            $order=['listorder'=>'desc','id'=>'desc'];
            $res=$this->where($data)->order($order)->limit($limit)->select();
            foreach ($res as $key=>$value)
            {
                if(strtotime($value->end_time)<time())
                {
                    unset($res[$key]);
                }
            }
            return $res;
        }

        /**
         * 根据条件获得商品
         * @param $data查询条件
         * @param $orders 排序
         * @return \think\Paginator 查询记录
         */
        public function getDealsByConditions($data,$orders)
        {
            $order=[];
            if(!empty($orders['order_sale']))
            {
                $order['buy_count']='desc';
            }
            elseif(!empty($orders['order_price']))
             {
                $order['current_price']='desc';
             }
             elseif(!empty($orders['order_time']))
             {
                $order['create_time']='desc';
             }
             else{
                $order['id']='desc';
             }
             $datas=[];

            if(!empty($data['city_id']))
            {
                $datas[]='city_id='.$data['city_id'];
            }
            if(!empty($data['category_id']))
            {
                $datas[]="category_id=".$data['category_id'];
            }
            $datas[]="status=1";
             return $this->where(implode(' AND ',$datas))->order($order)->paginate();
        }

        //更新团购商品总数
        public function increaseCount($id,$count=1)
        {
            $count=intval($count);
            $id=intval($id);
            if(!$count||!$id)
            {
                return false;
            }
            $oldCount=$this->where('id',$id)->total_count;
            $newCount=$oldCount-$count;
            if($newCount<0)
            {
                return false;
            }
            $res=$this->where('id',$id)->update(['total_count'=>$newCount]);
            if(!$res)
            {
                return false;
            }
            return true;
        }
    }
