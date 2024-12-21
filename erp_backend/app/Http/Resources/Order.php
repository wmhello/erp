<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
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
            'order_number' => $this-> order_number,
            'merchant_number' => $this-> merchant_number,
            'merchant_name' => $this-> merchant_name,
            'order_time' => $this->order_time,
            'status' => $this->status,
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
