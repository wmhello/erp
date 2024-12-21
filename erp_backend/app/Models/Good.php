<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    //
    protected $fillable = [
        'number', 'name', 'source', 'rule_size','rule_color','img', 'cate_id', 'cost', 'remark'
    ];

    public function scopeNumber($query)
    {
        $obj = request('number');
        if (isset($obj)) {
            return $query->where('number', 'like', '%'.$obj.'%');
        } else {
            return $query;
        }
    }
    public function scopeCateId($query)
    {
        $obj = request('cate_id');
        if (isset($obj)) {
            return $query->where('cate_id', $obj);
        } else {
            return $query;
        }
    }

    public function cate()
    {
        return $this->belongsTo('App\Models\Cate');
    }

    public function stock()
    {
        return $this->hasOne('App\Models\Stock');
    }

    public function storage()
    {
       return $this->hasMany('App\Models\Storage');
    }

    public function sell()
    {
       return $this->hasMany('App\Models\Sell');
    }

    public function purchase()
    {
       return $this->hasMany('App\Models\Purchase');
    }


}
