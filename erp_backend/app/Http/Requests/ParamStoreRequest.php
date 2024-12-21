<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParamStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
            'value'=> 'required',
            'name' => 'required|unique:params',
        ];
    }

    public function message()
    {
        return [
            'name.required' => '参数必须填写',
            'name.unique' => '参数名不能重复',
            'value.required' => '参数值必须填写',
        ];
    }

}
