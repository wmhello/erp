<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Storage extends Resource
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
            'good_count' => $this->good_count,
            'good_name' => $this->good->name,
            'date' => $this->date,
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