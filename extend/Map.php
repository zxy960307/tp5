<?php
/*
*封装百度地图相关业务
*/
	class Map
	{
		/*
		*通过地址获取经纬度
		*/
		public static function getLngLat($address)
		{
			$data=[
				'address'=>$address,
				'ak'=>config('map.ak'),
				'output'=>'json'
			];
			$url=Config('map.baidu_map_url').Config('map.geocoder')."?"
                .http_build_query($data);//使用给出的关联（或下标）数组生成一个经过 URL-encode 的请求字符串。
			
			$res=doCurl($url);
			if($res)
            {
                return json_decode($res,true);//将json转化为数组
            }
            else{
			    return [];
            }
		}
		
		//http://api.map.baidu.com/staticimage/v2

        /**
         * 根据指定经纬度返回地图静态图片文件流
         * @param $center地点或者经纬度
         * @return mixed图片文件流
         */
		public static function getMapImage($center)
		{
			$data=[
				'ak'=>config('map.ak'),
				'width'=>config('map.width'),
				'height'=>config('map.height'),				
				'center'=>$center,
				'markers'=>$center,				
			];
			$url=config('map.baidu_map_url').config('map.staticimage')."?"
                    .http_build_query($data);
			
			$res=doCurl($url);
			return $res;
		}

        /**
         * 返回地图静态图片src
         * @param $center指定地点或者经纬度
         * @return string文件位置
         */
        public static function getMapSrc($center)
        {
            $return_content = \Map::getMapImage($center);//返回图片文件流

            $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
            str_shuffle($str);//随机打乱字符串中所有字符
            $name=substr(str_shuffle($str),26,10);//10个长度的随机字符串

            $dir_name='baidumap/'.$name.'.jpg';
            $fp= @fopen($dir_name,"w");
            fwrite($fp,$return_content);

            fclose($fp);
            return  '/'.$dir_name;
        }
	}
