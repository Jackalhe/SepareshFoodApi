<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class GoodsModel extends Model
{
    protected $table = 'goods';
    protected $fillable = [
        'title',
        'comment',
        'goods_group_id',
        'price1',
        'price2',
        'price3',
        'price4',
        'price5',
        'price_discount1',
        'price_discount2',
        'price_discount3',
        'price_discount4',
        'price_discount5',
        'price1_title',
        'price2_title',
        'price3_title',
        'price4_title',
        'price5_title',
        'is_discount',
        'deliver_district',
        'is_active'
    ];

    public function GoodsGroup()
    {
        return $this->belongsTo(GoodsGroupModel::class,'goods_group_id','id');
    }

    public function Goods()
    {
        return $this->hasMany(GoodsAlbumModel::class, 'goods_id', 'id');
    }

}
