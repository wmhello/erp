<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    //
    protected $fillable = [
        'desc', 'value', 'name', 'remark'
    ];
}