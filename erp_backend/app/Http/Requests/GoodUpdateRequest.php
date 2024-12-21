<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoodUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $id = $this->route('good')->id;
        return [
            //
            'number'=> ['required',
                Rule::unique('goods')->ignore($id)],
            'cate_id' => 'required|exists:cates,id',
        ];
    }
}
