<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    //
    protected $fillable = ['package_id', 'product_id', 'product_amount', 'remark', 'order_id'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Package');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
