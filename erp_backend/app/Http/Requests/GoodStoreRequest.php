<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
            'number'=> 'required|unique:goods',
            'cate_id' => 'required|exists:cates,id',
        ];
    }

}
