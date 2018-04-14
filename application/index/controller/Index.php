<?php
namespace app\index\controller;

use think\Controller;
class Index extends Base
{
    //团购网首页
	public function index()
	{
        //获取推荐位信息
        $largeImg=model('Featured')->getNormalFeaturedsByType(0);//获取推荐位首页大图
        $guanggaoImg=model('Featured')->getNormalFeaturedsByType(1);//获取推荐位广告图

        //获取分类相关商品信息
        $deal[]=array();
        $cats=model('Category')->getNormalFirstCategorys();//获取所有status==1的一级分类
        foreach ($cats as $cat)
        {
            $deal[$cat->name]['deal']=model('Deal')->getDealsByCategoryCity($cat->id,$this->city->id);
            $deal[$cat->name]['cats']=model('Category')
                ->getRecommendCategorysByParentId($cat->id,4);
        }
        unset($deal[0]);

		return $this->fetch('',[
		    'largeImg'=>$largeImg,
            'guanggaoImg'=>$guanggaoImg,
            'deal'=>$deal,
        ]);
	}
	public function test()
    {
        return 'singwa';
    }

}
