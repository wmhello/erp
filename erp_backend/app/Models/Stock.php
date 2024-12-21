<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    protected $fillable = [
        'good_id', 'good_number', 'count', 'remark'
    ];

    public function Good()
    {
        return $this->belongsTo('App\Models\Good');
    }

    public function scopeGoodNumber($query)
    {
        $obj = request('good_number');
        if ($obj) {
            return $query = $query->where('good_number', 'like', '%'.$obj.'%');
        } else {
            return $query;
        }
    }
    public function scopeCateId($query)
    {
        $obj = request('cate_id');
        if ($obj) {
            return $query = $query->join('goods', 'stocks.good_id', '=', 'goods.id')->where('goods.cate_id', $obj);
        } else {
            return $query;
        }
    }
}
