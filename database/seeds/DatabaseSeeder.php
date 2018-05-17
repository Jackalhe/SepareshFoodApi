<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // php artisan make:seeder DatabaseSeeder
    //php artisan db:seed --class=DatabaseSeeder
    public function run()
    {
         ////$this->call(UsersTableSeeder::class);
        ///
        DB::table('employer')->insert([
            'title' => 'فست فود حوریا',
            'Comment' => 'انواع فست فود با 30 سال سابقه',
            'min_order' => '15000',
            'deliver_district' =>'شهرستان بابل',
            'address_comment' =>'بابل، خ بازار، کوچه ایثار 7، پلاک 105',
            'long' =>'1',
            'lat' =>'1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        $GoodsGroup = [[
            'title' => 'ساندویچ',
            'pic_path' => url('images/9.png'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'پینزا',
            'pic_path' => url('images/10.png'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'برگر',
            'pic_path' => url('images/11.png'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'پیش غذا',
            'pic_path' => url('images/12.png'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'نوشیدنی',
            'pic_path' => url('images/13.png'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]];


        DB::table('GoodsGroup')->insert($GoodsGroup);

        $Goods = [[
            'title' => 'ساندویچ هات داگ',
            'comment' => 'هات داگ ویژه با قارچ و پنیر اضافی',
            'goods_group_id' => 1,
            'price1' => 1000,
            'price2' => 2000,
            'price_discount1' => 200,
            'price_discount2' => 300,
            'price1_title' => 'عادی',
            'price2_title' => 'کوره ای',
            'is_discount' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'ساندویچ کوکتل',
            'comment' => '2 عدد کوکتل گوشت، خیار ، گوجه',
            'goods_group_id' => 1,
            'price1' => 1500,
            'price2' => 2500,
            'price_discount1' => 250,
            'price_discount2' => 350,
            'price1_title' => 'عادی',
            'price2_title' => 'کوره ای',
            'is_discount' => false,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'پیتزا استیک',
            'comment' => 'استیک گوساله، قارچ، زیتون، پنیر',
            'goods_group_id' => 2,
            'price1' => 2000,
            'price2' => 4000,
            'price_discount1' => 100,
            'price_discount2' => 200,
            'price1_title' => 'عادی',
            'price2_title' => 'تند',
            'is_discount' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],[
            'title' => 'پیتزا قارچ و گوشت',
            'comment' => 'گوشت چرخ کرده، قارچ ، ژامبون، پنیر',
            'goods_group_id' => 2,
            'price1' => 1000,
            'price2' => 2000,
            'price_discount1' => 200,
            'price_discount2' => 300,
            'price1_title' => 'عادی',
            'price2_title' => 'کوره ای',
            'is_discount' => false,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ],];


        DB::table('goods')->insert($Goods);

        $GoodsAlbum = [[
            'goods_id' => 1,
//            'pic_path' => public_path('images/1.jpg'),
//            'pic_path' => url('images/1.jpg'),
            'pic_path' => url('images/1.jpg'),
        ],[
            'goods_id' => 1,
            'pic_path' => url('images/2.jpg'),
        ],[
            'goods_id' => 2,
            'pic_path' => url('images/3.jpg'),
        ],[
            'goods_id' => 2,
            'pic_path' => url('images/4.png'),
        ],[
            'goods_id' => 3,
            'pic_path' => url('images/5.jpg'),
        ],[
            'goods_id' => 3,
            'pic_path' => url('images/6.jpg'),
        ],[
            'goods_id' => 4,
            'pic_path' => url('images/7.jpg'),
        ],[
            'goods_id' => 4,
            'pic_path' => url('images/8.jpg'),
        ]];


        DB::table('goodsalbum')->insert($GoodsAlbum);


    }
}
