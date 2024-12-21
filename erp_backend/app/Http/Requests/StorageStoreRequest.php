<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
            'good_id'=> 'required|exists:goods,id',
            'good_number' => 'required|exists:goods,number',
            'good_count' => 'required|numeric'
        ];
    }

}
