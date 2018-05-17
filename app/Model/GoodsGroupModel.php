<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsGroupModel extends Model
{
    protected $table = 'goodsgroup';
    protected $fillable = [
        'title',
        'pic_path'
    ];

    public function Goods()
    {
        return $this->hasMany(GoodsModel::class, 'goods_group_id', 'id');
    }

}
