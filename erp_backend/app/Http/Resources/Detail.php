<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Detail extends Resource
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
            'package_id' => $this-> package_id,
            'logistics_number' => $this->package->logistics_number,
            'logistics_date' => $this->package->logistics_date,
            'product_id' => $this->product_id,
            'product_name' => $this->product->product_name,
            'total_amount' => $this->product->product_amount,  // 该商品的总数量
            'product_amount' => $this-> product_amount,  // 该商品的打包数量
            'remark' => $this-> remark
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
