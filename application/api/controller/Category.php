<?php
    namespace app\api\controller;
    use think\Controller;

    class Category extends Controller{

        private $obj;

        public function getCategoryByParentId()
        {
            $this->obj=model('Category');
            $parent_id=input('post.id');

            $categorys=$this->obj->where('status','1')
                ->where('parent_id',$parent_id)
                ->order(['id'=>'desc'])
                ->select();

            return show(1,'success',$categorys);
        }
    }