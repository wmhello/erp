<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    protected $fillable = [
        'good_id', 'good_number', 'good_count', 'date', 'remark'
    ];

    public function good()
    {
        return $this->belongsTo('App\Models\Good');
    }

    public function scopeDate($query)
    {
        $date = request('date', null);
        if (isset($date)) {
            return $query = $query->where('date', $date);
        } else {
            return $query;
        }

    }
    public function scopeNumber($query)
    {
        $number = request('good_number', null);
        if (isset($number)) {
            return $query = $query->where('good_number','like', '%'.$number.'%');
        } else {
            return $query;
        }

    }

    public function scopeCateId($query)
    {
        $number = request('cate_id', null);
        if (isset($number)) {
            return $query = $query->leftJoin('goods', 'purchases.good_id', '=', 'goods.id')->where('goods.cate_id', $number);
        } else {
            return $query;
        }
    }

}
