<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name' => 'required|unique:cates'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品种类必须填写',
            'name.unique' => '商品种类不能重复'
        ];
    }
}
