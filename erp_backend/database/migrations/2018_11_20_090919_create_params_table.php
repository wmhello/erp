<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 程序字典、参数表
         */
        Schema::create('params', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique()->comment('参数名称');
            $table->string('desc', 50)->nullable()->comment('参数描述');
            $table->string('value')->comment('参数设置值');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('params');
    }
}
