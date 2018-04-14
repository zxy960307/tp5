<?php
    namespace app\index\controller;
    use think\Controller;

    class Detail extends Base
    {
        public function index()
        {
            //获取输入id
            $id=input('get.id');
            if(!$id)
            {
                $this->error('ID不合法');
            }

            //通过id查询商品
            $deal=model('Deal')->where('id',$id)
                ->where('status','1')
                ->find();
            if(!$deal)
            {
                $this->error('商品不存在');
            }


            //获取分类信息
            $category=model('Category')->where('id',$deal->category_id)->find();


            //获取分店信息
            $locations=model('BisLocation')->getLocations($deal->location_id);

            $flag=false;
            $timestr='';
            if(strtotime($deal->start_time)>time())
            {
                $flag=true;
                $timestr=getTimeStr(strtotime($deal->start_time)-time());
            }

            //获取地图信息
            $mapSrc=\Map::getMapSrc($locations[0]->xpoint.",".$locations[0]->ypoint);

            //获取商家信息
            $bis=model('Bis')->get($deal->bis_id);


            return $this->fetch('',[
                'timestr'=>$timestr,
                'title'=>$deal->name,
                'category'=>$category,
                'locations'=>$locations,
                'deal'=>$deal,
                'flag'=>$flag,
                'mapsrc'=>$mapSrc,
                'bis'=>$bis,
            ]);
        }
        public function getMap()
        {
            $data=input('get.data');
            return \Map::getMapSrc($data);
        }
    }