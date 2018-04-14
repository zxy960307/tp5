<?php
             namespace app\common\model;

             use think\Model;

             class BisLocation extends BaseModle
             {
                 public function getBranchLocation($locationId)
                 {
                     $bisId=$this->get('id',$locationId)->bis_id;
                    $branchRes=$this->where('bis_id',$bisId)
                        ->where('id_main',0)->select();
                    return $branchRes;
                 }

                 /**
                  * 通过门店路径获得门店信息
                  * @param $locationPath 门店路径
                  * @return array 门店信息数组
                  */
                 public function getLocations($locationPath)
                 {
                     $locations=[];
                     if(!$locationPath)
                     {
                         return [];
                     }
                    if(!preg_match('/,/',$locationPath))
                    {
                        $locations[0]=$this->where('id',$locationPath)->where('status',1)->find();
                        return $locations;
                    }

                    $locationIds=explode(',',$locationPath);
                     foreach ($locationIds as $locationId) {
                         $locations[]=$this->where('id',$locationId)->where('status',1)->find();
                    }
                    return $locations;
                 }
             }