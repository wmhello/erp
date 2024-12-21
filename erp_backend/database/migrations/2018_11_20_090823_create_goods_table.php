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
        /**
         * 商品表
         */
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 50)->unique()->comment('商品编号');
            $table->string('name')->nullable()->comment('商品名称');
            $table->string('source')->nullable()->comment('商品来源');
            $table->string('img')->nullable()->comment('商品图象');
            $table->integer('cate_id')->nullable()->comment('商品种类标识');
            $table->float('cost')->nullable()->comment('商品成本');
            $table->string('remark')->nullable()->comment('备注');
            $table->softDeletes();
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
        Schema::dropIfExists('goods');
    }
}
