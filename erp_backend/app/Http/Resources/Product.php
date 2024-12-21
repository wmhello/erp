<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Product extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this-> product_name,
            'product_number' => $this-> product_number,
            'product_img' => $this->product_img,
            'product_amount' => $this->product_amount,
            'remain_amount' => $this->remain_amount,
            'order_id' => $this->order_id,
            'buy_date' => $this-> buy_date,
            'remark' => $this->remark
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'status_code' => 200,
        ];
    }
}
