<?php
    namespace app\common\validate;
    use think\Validate;

    class User extends Validate
    {
        protected $rule=[
            'username'=>'require|max:20',
            'password'=>'alphaDash|require|max:20|min:5',
            'email'=>'email|require',
        ];
        protected $scene=[
            'login'=>['username','password'],
            'register'=>['username','password','email'],
        ];
        protected $message=[
            'username.require'=>'用户名不能为空',
            'username.max'=>'用户名长度不得超过10',
            'password.require'=>'密码不得为空',
            'password.alphaDash'=>'密码只能是字母、下划线_及破折号-',
            'password.min'=>'密码长度在5-20之间',
            'password.max'=>'密码长度在5-20之间',
            'email.require'=>'email必须填写',
            'email.email'=>'email格式不正确',
        ];
    }