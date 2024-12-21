<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Cacl extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//                "id": 2,
//                "good_id": 2,
//                "good_number": "P930-heimoshui",
//                "count": 84,
//                "remark": null,
//                "deleted_at": "2018-12-05 20:20:38",
//                "created_at": "2018-12-05 20:20:38",
//                "updated_at": "2018-12-05 20:20:38",
//                "name": "黑墨水",
//                "good_count": "43",
//                "agv_count": "1"

        return [
            'id' => $this->id,
            'good_id' => $this->good_id,
            'good_number' => $this->good_number,
            'count' => $this->count,
            'name' => $this->name,
            'good_count' =>$this->good_count,
            'agv_count' => $this->agv_count,
            'jj_count' => $this->jj_count,
            'kc_count' => $this->kc_count,
            'yj_day' => $this->yj_day
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
