<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParamUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $id = $this->route('param')->id;
        return [
            //
            'value'=> 'required',
            'name' => ['required',
                Rule::unique('params')->ignore($id)],
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
