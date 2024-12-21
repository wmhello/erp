<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellStoreRequest extends FormRequest
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
