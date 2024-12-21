<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaclsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 智能提示公式计算表
         */
        Schema::create('cacls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc')->comment('算法描述');
            $table->string('value')->comment('计算方式');
            $table->string('range')->nullable()->comment('范围（该算法适合的范围）指向cate_id');
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
        Schema::dropIfExists('cacls');
    }
}
