<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockStoreRequest;
use App\Http\Requests\StockUpdateRequest;
use App\Http\Resources\StockCollection;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class StockController extends Controller implements Model
{
    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $data = $model::GoodNumber()->CateId()->paginate($pageSize);
        return new StockCollection($data);

    }



    public function store(StockStoreRequest $request)
    {

            return $this->errorWithInfo('要增加商品信息，请到商品模块');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Stock($obj);

    }




    public function update(StockUpdateRequest $request, Stock $stock)
    {
        //
        $data = $request->except(['good_name', 'cate_id', 'good_img']);
        $model = $this->getModel();
        $obj = func_get_arg(1);
        if ($model::where('id', $obj->id)->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function destroy(Stock $stock)
    {
        //
        return $this->errorWithInfo('要删除商品信息，请到商品模块');
    }



    public function getModel()
    {
        return 'App\Models\Stock';
    }

    public function getExportFile() {
        return '库存表';
    }


    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $this->getFileData();
        $data = $this->uploadHandle($result);
        if ($data) {
            return $this->success();
        } else {
            return $this->errorWithInfo('数据导入失败，请检车文件格式是否正确');
        }
    }

    public function uploadHandle($data){
        $lists = [];
        foreach ($data as $v){
            $data = [
                'good_number' => $v['商品编号'],
                'count' =>$v['数量'],
                'updated_at' => Carbon::now(),
            ];
            DB::table('stocks')->where('good_number', $data['good_number'])->update($data);
            $lists[] = $data;
        }
        return $lists;
    }

    public function export()
    {
          $data = DB::table('stocks')
              ->select(DB::raw('stocks.id as 序号标识,stocks.good_number as 商品编号, goods.name as 商品名称, cates.name as 商品种类, stocks.count as 库存数量, goods.cost as 单价'))
              ->join('goods', 'goods.id','=', 'stocks.good_id')
              ->join('cates', 'cates.id', '=', 'goods.cate_id')
              ->get();
          $filePath = public_path('xls').'/export.xlsx';
         (new FastExcel($data))->export($filePath);
         return $this->successWithInfo('数据导出成功');
    }
}
