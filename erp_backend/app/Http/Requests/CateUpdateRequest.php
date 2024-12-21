<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CateUpdateRequest extends FormRequest
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
        $id = $this->route('cate')->id;
        return [
            'name' => [
                'required',
                Rule::unique('cates')->ignore($id)
            ],
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
