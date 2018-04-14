<?php
/**
 * 商家账户校验类
 */

    namespace app\common\validate;
    use think\Validate;
    class BisCount extends Validate
    {
        protected $rule=[
            'username'=>'require',
            'password'=>'require',
        ];
        protected $scene=[
            'add'=>['username','password'],
        ];
    }