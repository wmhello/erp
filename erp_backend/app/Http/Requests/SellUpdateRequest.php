<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
            'good_id'=> 'required',
            'good_count' => 'required|numeric'
        ];
    }
}
