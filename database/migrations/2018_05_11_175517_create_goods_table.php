<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('comment')->nullable();
            $table->unsignedInteger('goods_group_id');
            $table->decimal('price1', 18, 2)->default(0);
            $table->decimal('price2', 18, 2)->default(0);
            $table->decimal('price3', 18, 2)->default(0);
            $table->decimal('price4', 18, 2)->default(0);
            $table->decimal('price5', 18, 2)->default(0);
            $table->decimal('price_discount1', 18, 2)->default(0);
            $table->decimal('price_discount2', 18, 2)->default(0);
            $table->decimal('price_discount3', 18, 2)->default(0);
            $table->decimal('price_discount4', 18, 2)->default(0);
            $table->decimal('price_discount5', 18, 2)->default(0);
            $table->string('price1_title')->nullable();
            $table->string('price2_title')->nullable();
            $table->string('price3_title')->nullable();
            $table->string('price4_title')->nullable();
            $table->string('price5_title')->nullable();
            $table->boolean('is_discount')->default(false);
            $table->string('deliver_district')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('goods_group_id')->references('id')->on('goodsgroup');
        });
//        $table->foreign('user_id')
//            ->references('id')->on('users')
//            ->onDelete('cascade');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
