<?php
    namespace app\common\validate;
    use think\Validate;

    class Deal extends Validate
    {
        protected $rule=[
            'name'=>'require|max:40',
            'city_id'=>'require',
            'category_id'=>'require',
            'image'=>'require',
            'start_time'=>'require',
            'end_time'=>'require',
            'location_ids'=>'require',
            'coupons_begin_time'=>'require',
            'coupons_end_time'=>'require',
            'origin_price'=>'require|float|gt:0',//原价必须为浮点数且大于0
            'current_price'=>'require|float|lt:origin_price|>:0',//现价必须为浮点数且小于原价
            'total_count'=>'require|egt:1|number',//总数为整数必须大于等于1
        ];
        protected $scene=[
            'add'=>['name','city_id','image','start_time','end_time','total_count',
                    'coupons_begin_time','coupons_end_time','origin_price','current_price'],
        ];
        protected $message=[
            'location_ids'=>'必须选择至少一家门店',
            'name.require'=>'必须填入团购名称',
            'name.max'=>'团购名长度不得超过20',
            'city_id'=>'必须选择城市',
            'image'=>'缩略图不能空白',
            'start_time.require'=>'团购开始时间必须填入',
            'end_time.require'=>'团购结束时间必须填入',
            'coupons_begin_time.require'=>'消费券生效时间必须填入',
            'coupons_end_time'=>'消费券结束结束时间必须填入',
            'origin_price.require'=>'原价必须填入',
            'origin_price.float'=>'原价格式不正确',
            'origin_price.gt'=>'原价必须大于0',
            'current_price.require'=>'团购价必须填入',
            'current_price.float'=>'团购价格式不正确',
            'current_price.gt'=>'团购价必须大于0',
            'current_price.lt'=>'团购价不得高于原价',
            'total_count'=>'库存数格式不正确',
            'category_id'=>'分类必须选择',
        ];
    }