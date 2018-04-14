<?php
namespace app\admin\controller;

use think\Controller;
class Category extends Controller
{
    public function index()
    {
		$parent_id=input('get.parent_id',0,'intval');
		$categorys=model('Category')->getFirstCategorys($parent_id);
        return $this->fetch('',['categorys'=>$categorys]);
    }
	
	public function add()
	{

		$categorys=model('Category')->getNormalFirstCategorys();//实例化一个Category模板类并进行查询
		return $this->fetch('',['categorys'=>$categorys,]);
	}
	public function edit()
	{
		$category=model('Category')->get(input("get.id"));
		$categorys=model('Category')->getNormalFirstCategorys();
		return $this->fetch('',['category'=>$category,'categorys'=>$categorys]);
	}
	public function save()
	{
		$data=request()->post();
		$validate=validate('Category');
		if(!$validate->scene('add')->check($data))
		{
			$this->error($validate->getError());
		}
		
		/*将数据写入数据库*/
		if(!empty($data['id']))//id存在，进行更新操作
		{
			$this->update($data);
		}
		else//id不存在，add操作
		{
			$res=model('Category')->add($data);
			if($res)
			{
				$this->success('新增分类成功');
			}
			else
			{
				$this->error('分类写入数据库失败');
			}
		}
		
	}
	/*
	*修改分类状态
	*/
	public function status()
	{
		$status=intval(input("get.status"));//获取status及id
		$id=intval(input("get.id"));
		$model=model('Category');//初始化model变量
		if($status==(-1))
		{
			$model->save(['status'=>$status],['parent_id'=>$id]);//若执行删除操作，则改变二级分类状态
		}
		$res=$model->where('id',$id)->update(['status'=>$status]);//更新分类状态
		if($res)
		{
			$this->success("更新状态成功");
		}
		else{
			$this->error('更新状态失败');
		}
	}

    /**
     * 更新数据库记录
     * @param $data数据库记录更新内容
     */
	public function update($data)
	{
		$model=model('Category');//初始化model变量
		$res=$model->save($data,['id'=>intval($data['id'])]);//更新记录
		if($res)
		{
			$this->success('更新成功','',2);
		}
		else
		{
			$this->error('更新失败');
		}
	}
}
