<?php
/**
 * 门店信息校验类
 */
    namespace app\common\validate;
    use think\Validate;
    class BisLocation extends Validate
    {
        protected $rule=[
            'tel'=>'require|number',
            'contact'=>'require',
            'category_id'=>'require',
            'address'=>'require',
            'open_time'=>'require',
        ];
        protected $scene=[
            'add'=>['name','tel','contact','category_id','address','open_time',],
        ];
    }