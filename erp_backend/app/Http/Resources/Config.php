<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Config extends Resource
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
            'sub_number' => $this->sub_number,
            'numbers' => $this->numbers,
            'counts' => $this->counts,
            'good_ids' =>$this->good_ids,
            'tips' => $this->tips,
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
