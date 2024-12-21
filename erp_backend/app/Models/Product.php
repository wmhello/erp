<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['product_name', 'product_number', 'product_img','product_amount','remain_amount','order_id','buy_date', 'remark'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function details()
    {
        return $this->hasMany('App\Models\Detail');
    }
}
