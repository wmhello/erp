<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CaclUpdateRequest extends FormRequest
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
        $id = $this->route('cacl')->id;
        return [
            //
            'desc'=> [
                'required',
                Rule::unique('cacls')->ignore($id),
            ],
            'value'=> [
                'required',
                Rule::unique('cacls')->ignore($id),
            ],

        ];
    }

    public function messages() {
        return [
            //
            'desc.required'=> '算法描述必须填写',
            'desc.unique'=> '算法描述必须唯一',
            'value.required'=> '智能算法必须填写',
            'value.unique'=> '智能算法必须唯一',

        ];
    }

}
