<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Good extends Resource
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
            'name' => $this->name,
            'number' => $this->number,
            'source' => $this->source,
            'rule_size' => $this->rule_size,
            'rule_color' => $this->rule_color,
            'img' => $this->img,
            'cate_id' => $this->cate_id,
            'cate_name' =>isset($this->cate->name)?$this->cate->name:'无',
            'cost' => $this->cost,
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
