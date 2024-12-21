<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
    protected $fillable = ['logistics_number','logistics_date', 'remark'];

    public function details()
    {
        return $this->hasMany('App\Models\Detail');
    }
}
