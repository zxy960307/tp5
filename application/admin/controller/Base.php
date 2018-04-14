<?php
    namespace app\admin\controller;
    use think\Controller;

    class Base extends Controller
    {
        /**
         * 改变id对应数据表记录中status值
         * @return $this 更新成功返回1，否则返回0
         */
        public function status()
        {
            $data=input('get.');//获取值
            //校验
            if(empty($data['id']))
            {
                $this->error('id不合法');
            }
            elseif(!is_numeric($data['status']))
            {
                $this->error('status不合法');
            }

            //更新数据库中status
            $controllerName=request()->controller();//获取控制器
            $res=model($controllerName)->where('id',$data['id'])
                ->update(['status'=>$data['status']]);
            if($res)
            {
                $this->success('更新状态成功');
            }
            else{
                $this->error('更新状态失败');
            }
        }
    }