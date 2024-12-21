<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaclStoreRequest extends FormRequest
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
            'desc'=> 'required|unique:cacls',
            'value'=> 'required|unique:cacls',

        ];
    }

    public function messages() {
        return [
            //
            'desc.required'=> '算法描述必须填写',
            'desc.unique'=> '算法描述必须唯一',
            'value.required'=> '算法必须填写',
            'value.unique'=> '智能算法必须唯一',

        ];
    }
}
