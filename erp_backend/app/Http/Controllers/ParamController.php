<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParamStoreRequest;
use App\Http\Requests\ParamUpdateRequest;
use App\Http\Resources\ParamCollection;
use App\Models\Param;
use Illuminate\Http\Request;

class ParamController extends Controller implements Model
{
    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $data = $model::paginate($pageSize);
        return new ParamCollection($data);

    }



    public function store(ParamStoreRequest $request)
    {
        $data = $request->all();
        $model = $this->getModel();
        if ($model::create($data)) {
            return $this->success();
        } else  {
            return $this->error();
        }
    }

    public function show(Param $param)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Param($obj);

    }




    public function update(ParamUpdateRequest $request, Param $param)
    {
        //
        $data = $request->all();
        $model = $this->getModel();
        $obj = func_get_arg(1);
        if ($model::where('id', $obj->id)->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function destroy(Param $param)
    {
        //
        $obj = func_get_arg(0);
        if ($obj->delete()){
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function getModel()
    {
        return 'App\Models\Param';
    }

    public function getExportFile() {
        return '数据字典';
    }
}
