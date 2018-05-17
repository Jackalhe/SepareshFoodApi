<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAlbumModel extends Model
{
    protected $table = 'goodsalbum';
    protected $fillable = [
        'goods_id',
        'pic_path'
    ];

    public function Goods()
    {
        return $this->belongsTo(GoodsModel::class,'goods_id','id');
    }
}
