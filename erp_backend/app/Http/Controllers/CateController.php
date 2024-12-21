<?php

namespace App\Http\Controllers;

use App\Http\Requests\CateStoreRequest;
use App\Http\Requests\CateUpdateRequest;
use App\Http\Resources\CateCollection;
use App\Models\Cate;
use Carbon\Carbon;

class CateController extends Controller implements Model
{

    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $data = Cate::paginate($pageSize);
        return new CateCollection($data);

    }


    public function store(CateStoreRequest $request)
    {
        //
        $data = $request->all();
        $model = $this->getModel();
        if ($model::create($data)) {
            return $this->success();
        } else  {
            return $this->error();
        }

    }


    public function show(Cate $cate)
    {
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Cate($obj);
    }



    public function update(CateUpdateRequest $request, Cate $cate)
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


    public function destroy(Cate $cate)
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
         return 'App\Models\Cate';
    }

    public function getExportFile() {
        return '商品种类';
    }

    public function uploadHandle($data){
        $lists = [];
        foreach ($data as $v){
            $data = [
                'name' => $v['种类名称(name)'],
                'remark' =>$v['备注(remark)'],
                'pid' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $lists[] = $data;
        }
        return $lists;
    }

    public function getAll()
    {
        $data = Cate::select('id', 'name')->orderBy('id')->get('id', 'name');
        return $this->successWithData($data);
    }


}
