<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['order_number', 'merchant_number', 'merchant_name','order_time','status', 'remark'];

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function scopeOrderNumber($query)
    {
        $obj = request('order_number');
        if (isset($obj)) {
            return $query->where('order_number', 'like', '%'.$obj.'%');
        } else {
            return $query;
        }
    }
    public function scopeMerchantName($query)
    {
        $obj = request('merchant_name');
        if (isset($obj)) {
            return $query->where('merchant_name', 'like', '%'.$obj.'%');
        } else {
            return $query;
        }
    }

    public function scopeMerchantNumber($query)
    {
        $obj = request('merchant_number');
        if (isset($obj)) {
            return $query->where('merchant_number', 'like', '%'.$obj.'%');
        } else {
            return $query;
        }
    }
    public function scopeStatus($query)
    {
        $obj = request('status');
        if (isset($obj)) {
            return $query->where('status', $obj);
        } else {
            return $query;
        }
    }
}
