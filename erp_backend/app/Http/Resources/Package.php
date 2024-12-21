<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Package extends Resource
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
            'logistics_number'=> $this->logistics_number,
            'logistics_date'=> $this-> logistics_date,
            'details' => $this->details,
            'remark'=> $this->remark
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
