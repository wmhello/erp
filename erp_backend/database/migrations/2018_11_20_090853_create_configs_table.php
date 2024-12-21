<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 导入信息配置表
         */
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sub_number',50)->comment('商品子类型');
            $table->string('good_number',50)->comment('商品类型');
            $table->unsignedInteger('good_id')->comment('商品标识');
            $table->unsignedInteger('count')->comment('商品数量');
            $table->string('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('sub_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
