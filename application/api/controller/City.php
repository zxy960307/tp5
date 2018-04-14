<?php
namespace app\api\controller;

use think\Controller;
class City extends Controller
{
    public function getCitysByParentId()
    {
        $parent_id=input('post.id');
        $city=model('City');
        $citys=$city->getNormalCitysByParentid($parent_id);

        return show(1,'success',$citys);
    }
}