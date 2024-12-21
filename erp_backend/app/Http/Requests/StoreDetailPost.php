<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetailPost extends FormRequest
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
            'package_id' => 'required|exists:packages,id',
            'product_amount' => 'required|integer',
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
