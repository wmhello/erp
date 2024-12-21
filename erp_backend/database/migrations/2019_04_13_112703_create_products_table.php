<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name')->nullable()->comment('产品名称');
            $table->string('product_number')->comment('产品编号');
            $table->string('product_img')->nullable()->comment('产品图象');
            $table->unsignedInteger('product_amount')->default(0)->comment('产品数量');
            $table->unsignedInteger('remain_amount')->default(0)->comment('待分割数量');
            $table->integer('order_id')->comment('所属订单');
            $table->timestamp('buy_date')->comment('购买日期');
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
        Schema::dropIfExists('products');
    }
}
