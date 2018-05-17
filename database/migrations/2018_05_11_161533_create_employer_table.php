<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('Comment')->nullable();
            $table->decimal('min_order', 18, 2)->default(1);
            $table->string('deliver_district')->nullable();
            $table->string('address_comment');
            $table->time('sat_start')->default('08:00');
            $table->time('sun_start')->default('08:00');
            $table->time('mon_start')->default('08:00');
            $table->time('tue_start')->default('08:00');
            $table->time('wed_start')->default('08:00');
            $table->time('thu_start')->default('08:00');
            $table->time('fri_start')->default('08:00');
            $table->time('sat_end')->default('00:00');
            $table->time('sun_end')->default('00:00');
            $table->time('mon_end')->default('00:00');
            $table->time('tue_end')->default('00:00');
            $table->time('wed_end')->default('00:00');
            $table->time('thu_end')->default('00:00');
            $table->time('fri_end')->default('00:00');
            $table->decimal('long', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employer');
    }
}
