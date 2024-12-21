<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    //
    protected $fillable = [
        'sub_number', 'good_number', 'good_id', 'count','remark'
    ];



    public function scopeSubNumber($query)
    {
        $number = request('sub_number', null);
        if (isset($number)) {
            return $query = $query->where('sub_number', $number);
        } else {
            return $query;
        }

    }
}
