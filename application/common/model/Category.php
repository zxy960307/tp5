<?php
namespace app\common\model;

use think\Model;


class Category extends Model
{
	protected $autoWriteTimestamp = true;//开启自动写入时间戳
	public function add($data)
	{
		$data['status']=1;
		return $this->save($data);//save()方法增加一条记录，返回添加的记录数
	}
	
	/*获取正常一级栏目*/
	public function getNormalFirstCategorys()
	{
		$data=[
			'parent_id'=>0,
			'status'=>1
		];
		$order=['id'=>'desc'];
		return $this->where($data)->order($order)->select();//进行数据库查询一级分类
	}
	
	/*获取非删除状态一级栏目*/
	public function getFirstCategorys($parent_id=0)
	{
		$order=[
			'id'=>'desc'
		];
		{
			return $this->where('status','neq',-1)
			->where('parent_id',$parent_id)
			->order($order)
			->paginate(5);
		}
/* 		else
		{
			return $this->where('status','neq',-1)
			->where('parent_id',$parent_id)
			->order($order)
			->select();
		} */
	}

    /**
     * 通过id获取一级分类
     * @param int $id 父级id
     * @param int $limit 获取记录数
     * @return $this 记录
     */
	public function getRecommendCategorysByParentId($id=0,$limit=5)
    {
        $data=['parent_id'=>$id,'status'=>1];
        $order=['name'=>'desc','id'=>'desc'];
        $result=$this->where($data)->order($order);
        if($limit)
        {
            $result=$result->limit($limit)->select();
        }
        else{
            $result=$result->limit(5)->select();
        }
        return $result;
    }

    /**
     * 通过父级id获取子分类信息
     * @param $ids id
     * @return false|\PDOStatement|string|\think\Collection 子分类信息
     */
    public function getNormalRecommendCategorysById($ids)
    {
        $order=['name'=>'desc','id'=>'desc'];
        $result=$this->where('status',1)
            ->where('parent_id','in',implode(',',$ids))
            ->order($order)
            ->select();
        return $result;
    }
}


// model:数据库中每张表对应一个模型
// 类名就是表名，类里面的成员变量就是列名
// 把一张表对应一个类，其中一条数据对应一个对象