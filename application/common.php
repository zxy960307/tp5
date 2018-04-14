<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
	function status($status)
	{
		if($status==1)
		{
			$str="<span class='label label-success radius'>正常</span>";
		}
		elseif($status==0)
		{
			$str="<span class='label label-danger radius'>待审</span>";
		}
        elseif($status==2)
        {
            $str="<span class='label label-danger radius'>未通过</span>";
        }
        elseif($status==-1)
        {
            $str="<span class='label label-danger radius'>已下架</span>";
        }
		return $str;
	}
	
	function doCurl($url,$data=[],$type=0)
	{
		$ch=curl_init();//初始化一个curl对话并返回一个curl句柄
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//获取页面内容，不直接输出
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		
		if($type==1)
		{
			curl_setopt($ch,CURLOPT_POST,1);//type为1时post方式提交
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//
		}
					
			//执行curl
			$output=curl_exec($ch);
			
			//释放句柄
			curl_close($ch);
			//返回执行结果
			return $output;
	}

/**
 * 商户申请入驻文案
 * @param int $status审核状态
 * @return string 返回审核状态字符串
 */
	function bisRegister($status=0)
    {
        switch($status)
        {
            case 1:$str='商户申请入住成功';break;
            case 0:$str='商户申请入驻待审核';break;
            case 2:$str='商户入驻申请未通过,请重新提交';break;
            default:$str='入驻申请已被删除';
        }
        return $str;
    }

    function pagination($obj)
    {
        if(!$obj)
        {
            return '';
        }
        $param=request()->param();//获取当前请求的参数
        //appends 添加url参数
        return 	"<div class='cl pd-5 bg-1 bk-gray mt-20 tp5-o2o'>".$obj->appends($param)->render()."</div>";
    }

/**
 * 获得二级城市名
 * @param $city_path城市路径
 */
    function getSeCityName($city_path)
    {
        if(!$city_path)
        {
            return '';
        }
        if(!preg_match('/,/',$city_path))
        {
            return '';

        }
        $cityPath=explode(',',$city_path);
        $cityid=$cityPath[1];
        return model('City')->get($cityid)->getData()['name'];
    }
    function getCityName($city_id)
    {
        if(!$city_id)
        {
            return '';
        }
        return model('City')->get($city_id)->getData()['name'];
    }

/**
 * 根据分类id获得分类名
 * @param $category_id
 * @return array
 * @throws \think\exception\DbException
 */
    function getCategoryName($category_id)
    {
        if(!$category_id)
        {
            return [];
        }
        return model('Category')->get($category_id)->getData()['name'];
    }

/**
 * 通过分类路径获得二级分类id
 * @param $category_path 分类路径
 * @return array 数组id
 */
    function getSeCategoryId($category_path)
    {
        //判定$category_path是否存在
        if(!$category_path)
        {
            return [];
        }
        //判定是否存在二级分类
        $secategoryIds=explode(',',$category_path)[1];//二级分类id字符串
        if(!(bool)$secategoryIds)
        {
            return [];
        }
        $seCategoryIdArray=explode('|',$secategoryIds);//二级分类id数组
        return $seCategoryIdArray;
    }

    function main($is_main)
    {
        if($is_main==0)
        {
            return "<span class='label label-success radius'>否</span>";
        }
        else if($is_main==1)
        {
            return "<span class='label label-success radius'>是</span>";
        }
        return '';
    }

/**
 * 为session设置过期时间
 * @param int $value 过期时间，单位为秒
 * @return bool 设置成功后返回true
 */
    function set_session_time($value=3600)
    {
        ini_set('session.gc_maxlifetime',60);
        return true;
    }

/**
 * 通过所有门店id获取门店数目
 * @param $idsi门店id字符串
 * @return int  门店数目
 */
    function countLocatin($ids)
    {
        if(!preg_match('/,/',$ids))
        {
            return 1;
        }
        $ids=explode($ids,',');
        return count($ids);
    }

/**
 * 获取一段时间戳的时分秒表示
 * @param $dtime
 * @return string
 */
    function getTimeStr($dtime)
    {
        $timestr='';
        $d=floor($dtime/(3600*24));
        if($d)
        {
            $timestr.=$d."天";
        }
        $h=floor($dtime%(3600*24)/3600);
        if($h)
        {
            $timestr.=$h."小时";
        }
        $m=floor($dtime%(3600*24)%3600/60);
        if($m)
        {
            $timestr.=$m."分钟";
        }
        return $timestr;
    }

    //设置订单号
    function setOrderSn()
    {
        list($t1,$t2)=explode(' ',microtime());//microtime()返回unix时间戳的微秒，
                                                        // list()将一些数组中的值赋值给变量
        $t3=explode('.',$t1*10000);
        return $t2.$t3[0].(rand(10000,99999));
    }
