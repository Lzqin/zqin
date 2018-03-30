<?php

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    protected $hidden = ['update_time','delete_time','id','img_id','banne_id'];
    
    public function img(){
         return $this->belongsTo('Image' , 'img_id' , 'id');
    }
}
