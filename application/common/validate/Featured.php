<?php
    namespace app\common\validate;
    use think\Validate;

    class Featured extends Validate
    {
        protected $rule=[
            'title'=>'require|max:20',
            'image'=>'require',
            'url'=>'url',
            'type'=>'require',
        ];
        protected $message=[
            'title'=>'标题格式不正确',
            'image'=>'推荐图不存在',
            'url'=>'url格式不正确',
            'type'=>'必须选择分类',
        ];
    }