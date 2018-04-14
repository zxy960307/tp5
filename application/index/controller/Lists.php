<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/11
 * Time: 15:39
 */
    namespace app\index\controller;
    use think\Controller;

    class Lists extends Base
    {
        public function index()
        {
            $id=intval(input('get.id',0));
            $categorys=model('Category')->getNormalFirstCategorys();//所有一级分类
            $firstCatsId=[];
            foreach ($categorys as $category)
            {
                $firstCatsId[]=$category->id;
            }

            //获取分类
            $data=[];
            $data['status']=1;
            //一级分类
            if(in_array($id,$firstCatsId))
            {
                $categoryParentId=$id;
                $data['category_id']=$id;
            }
            //二级分类
            elseif($id)
            {
                $category=model('Category')->where('status',1)
                    ->where('id',$id)->find();
                if(!$category)
                {
                    $this->error('数据不合法');
                }
                $data['se_category_id']=$id;
                $categoryParentId=$category->parent_id;
            }
            //其他
            else{
                $categoryParentId=0;
            }

            //获取父类下所有子分类
            $sedCategorys=[];
            if($categoryParentId)
            {
                $sedCategorys=model('Category')->where(['parent_id'=>$categoryParentId,
                    'status'=>1])->select();
            }

            //排序逻辑
            $order_sale=input('get.order_sale','');
            $order_price=input('get.order_price','');
            $order_time=input('get.order_time','');
            $orders=[];
            if(!empty($order_sale))
            {
                $orderflag='order_sale';
                $orders['order_sale']=1;
            }
            elseif(!empty($order_price))
            {
                $orderflag='order_price';
                $orders['order_price']=1;
            }
            elseif (!empty($order_time))
            {
                $orderflag='order_time';
                $orders['order_time']=1;
            }
            else{
                $orderflag='default';
            }


            $deals=model('Deal')->getDealsByConditions($data,$orders);

            //将结束时间大于当前时间的记录删除
            //将没有当前要求二级分类的记录删除
            foreach ($deals as $key=>$deal)
            {
                if(strtotime($deal->end_time)<time())
                {
                    unset($deals[$key]);continue;
                }
                if(!empty($data['se_category_id']))
                {
                    if(!empty($deal->se_category_id))
                    {
                        $seCats=explode(',',$deal->se_category_id);
                        $flag=false;//若存在二级分类相同，则置flag为true
                        foreach ($seCats as $value)
                        {
                            if($value==$data['se_category_id'])
                            {
                                $flag=true;break;
                            }
                        }
                        if($flag==false)
                        {
                            unset($deals[$key]);
                        }
                    }
                    else{
                        unset($deals[$key]);
                    }
                }
            }
            return $this->fetch('',[
                'categorys'=>$categorys,
                'id'=>$id,
                'categoryparentid'=>$categoryParentId,
                'sedcategorys'=>$sedCategorys,
                'orderflag'=>$orderflag,
                'deals'=>$deals,
            ]);
        }
    }