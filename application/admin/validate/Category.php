<?php
	namespace app\admin\validate;
	use think\Validate;
	class Category extends Validate{
		/*验证规则设置*/
		protected $rule=[
			'name'=>'require|max:10',
			'status'=>'number|in:0.-1,1',
			'id'=>'number',
			'listorder'=>'number',
			'parentid'=>'number'
		];
		/*验证属性设置*/
		protected $message=
		[
			'name.require'=>'分类名必须填写',
			'name.max'=>'长度不可超过10个字符哦'
		];
		/*验证场景设置*/
		protected $scene=
		[
			'add'=>['name','parentid','id'],
			'listorder'=>['id','listorder'],
			'edit'=>['id','name','parentid']
		];
	}
