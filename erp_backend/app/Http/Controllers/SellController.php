<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellStoreRequest;
use App\Http\Requests\SellUpdateRequest;
use App\Http\Resources\SellCollection;
use App\Models\Config;
use App\Models\Sell;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class SellController extends Controller implements Model
{


    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $data = $model::Date()->Number()->orderBy('id', 'desc')->paginate($pageSize);
        return new SellCollection($data);

    }



    public function store(SellStoreRequest $request)
    {
        $data = $request->all();
        if ($data['good_type'] === '基本商品') {
           $data = array_except($data, 'config_number');
            $data = array_except($data, 'good_type');
            $model = $this->getModel();
            if ($model::create($data)) {
                return $this->success();
            } else  {
                return $this->error();
            }
        } else  if ($data['good_type'] === '配对商品') {  // 配对商品分开处理
//          dd($data);
//            array:7 [
//                "good_id" => 46
//                  "good_type" => "配对商品"
//                  "config_number" => "g111"
//                  "good_number" => null
//                  "good_count" => 4
//                  "date" => "2018-12-02"
//                  "remark" => null
//                ]

          $configs =  Config::where('sub_number', $data['config_number'])->get(['good_number', 'good_id', 'count']);
          foreach ($configs as $k => $v){
              $item = [
                  'good_id' => $v['good_id'],
                  'good_number' => $v['good_number'],
                  'good_count' => $data['good_count'] * $v['count'],
                  'date' => $data['date'],
                  'remark' => $data['remark']
              ];
              Sell::create($item);
          }
          return $this->success();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function show(Sell $sell)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Sell($obj);

    }




    public function update(SellUpdateRequest $request, Sell $sell)
    {
        //

        $data = $request->all();
        $model = $this->getModel();
        $data = array_except($data, 'good_name');
        $data = array_except($data, 'good_type');

        $obj = func_get_arg(1);
        if ($model::where('id', $obj->id)->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function destroy(Sell $sell)
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
        return 'App\Models\Sell';
    }

    public function getExportFile() {
        return '销售表';
    }

    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $this->getFileData();
        return $this->uploadHandle($result);
    }

    public function uploadHandle($data){
        //dd($data);
//        0 => array:2 [
//            "商品编号" => "test1"
//            "数量" => 52
//          ]

        $uploadDate = request('uploadDate', Carbon::now());
        // 获得基本商品编号和配对编号
        $numberData = DB::select('select  id, goods.number as number, \'goods\' as tables from goods UNION select id, sub_number as number, \'configs\' as tables from configs');
        // 获取配对信息表
        $configData = DB::select('select id, sub_number, good_number, count, good_id FROM configs');

        $configCollection = collect($configData);
        $numberCollection = collect($numberData);


        foreach ($data as $v){
            if (empty($v['商品编号']) && empty($v['数量'])){
                break;
            }

            // 1、检测当前的编号在商品编号和配对编号中是否存在
            $filter = $numberCollection->filter(function($value,$key) use ($v){
                return $value->number === ucfirst($v['商品编号']) or $value->number===$v['商品编号'];
            });
            // 2、不存在 则开始下一次循环，导入的编号内容作废
            if ($filter->isEmpty()) {
                // 没有指定的商品  退出
                $this->errorCount++;
                $this->error = true;
                $v['出错提示'] = '指定的商品编号不存在';
                $this->arrErrors[] = $v;
                continue;
            }
            // 3、存在指定的编号，则分两种方式来处理，一种是基础编号，一种是配对编号
            $this->successCount++;
            $tables = $filter->first()->tables;
            // 3.1 基础编号处理，根据内容直接插入销售表，处理完成后开始导入下一个数据
            if ($tables === 'goods'){
              $item = [
                'good_id' => $filter->first()->id,
                'good_number' => $filter->first()->number,
                'good_count'  => $v['数量'],
                'date' => $uploadDate
              ];
              Sell::create($item);
                  continue;
            }
            // 3.2 配对编号的处理，要根据编号选择出对应的基础编号项目，然后再次分开插入到销量表
            if ($tables === 'configs') {
                $filter = $configCollection->filter(function($value,$key) use ($v){
                    return $value->sub_number === ucfirst($v['商品编号']) or $value->sub_number===$v['商品编号'];
                });
                if ($filter->isEmpty()) {
                    // 没有指定的商品  退出
                    continue;
                }
                // 3.3. 依次插入到销量表
                $filter->each(function($value, $key) use($v, $uploadDate) {
                    $item = [
                       'good_id' => $value->good_id,
                        'good_number' => $value->good_number,
                        'good_count' => $value->count * $v['数量'],
                        'date' => $uploadDate
                    ];
                    Sell::create($item);
                });
            }
        }
        return $this->returnTips();
    }


    public function test()
    {
        $value = Cache::remember('users', -1,function () {
            return DB::table('users')->get();
        });
        return $this->successWithData($value);
    }

}
