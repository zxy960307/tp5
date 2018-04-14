<?php
/**
 * 商家信息校验类
 */
    namespace app\common\validate;
    use think\Validate;

    class Bis extends Validate
    {
        protected $rule=[
            'name'=>'require|max:20',
            'email'=>'require|email',
            'logo'=>'require',
            'licence_logo'=>'require',
            'city_id'=>'require',
            'bank_info'=>'require|number',
            'bank_name'=>'require',
            'bank_user'=>'require|max:10',
            'faren'=>'require',
            'faren_tel'=>'require',
        ];
        protected  $scene=[
            'add'=>['name','email','logo','licence_logo','city_id',
                    'bank_info','bank_name','bank_user','faren','faren_tel'],
        ];
    }