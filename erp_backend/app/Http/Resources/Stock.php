<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Stock extends Resource
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
            'good_id' => $this->good_id,
            'good_number' => $this->good_number,
            'good_name'=>$this->good->name,
            'cate_id'=>$this->good->cate_id,
            'good_img' => $this->good->img,
            'count' => $this->count,
            'remark' => $this->remark
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'status_code' => 200
        ];
    }
}
