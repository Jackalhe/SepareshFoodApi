<?php

namespace App\Http\Controllers\Api\v1;

use App\Model\GoodsAlbumModel;
use App\Model\GoodsGroupModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller
{

    public function getmenu()
    {

//        $goods = DB::table('goods')
//            ->leftjoin('goodsgroup', 'goods.goods_group_id', '=', 'goodsgroup.id')
//            ->select('goods.*', 'goodsgroup.title as goodsgroup','goodsgroup.pic_path as goodsgroup_pic_path')
//            ->get();

        $GoodsModel = GoodsGroupModel::all();
        $Goods = GoodsModel::where('is_active',true)->get();
        $GoodsAlbum = GoodsAlbumModel::all();

//        return response()->download(public_path('images/1.jpg'),'33',['ff'=>'555']);
//        return response()->json(['code'=>'1002','message'=>'goods data was successfully received', 'Goods'=>$goods, 'GoodsAlbum'=>$GoodsAlbum], 200);
        return response()->json(['code'=>'1002','message'=>'goods data was successfully received', 'GoodsModel'=>$GoodsModel, 'Goods'=>$Goods, 'GoodsAlbum'=>$GoodsAlbum], 200);
    }


}
