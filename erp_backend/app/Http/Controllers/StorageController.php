<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorageStoreRequest;
use App\Http\Requests\StorageUpdateRequest;
use App\Http\Resources\StorageCollection;
use App\Models\Good;
use App\Models\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class StorageController extends Controller implements Model
{


    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $data = $model::Date()->Number()->orderBy('id', 'desc')->paginate($pageSize);
        return new StorageCollection($data);

    }



    public function store(StorageStoreRequest $request)
    {
        $data = $request->all();
        $model = $this->getModel();
        if ($model::create($data)) {
            return $this->success();
        } else  {
            return $this->error();
        }
    }


    public function show(Storage $storage)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Storage($obj);

    }


    public function update(StorageUpdateRequest $request, Storage $storage)
    {
        //
        $data = $request->except(['good_name']);
        $model = $this->getModel();
        $obj = func_get_arg(1);
        if ($model::where('id', $obj->id)->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function destroy(Storage $storage)
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
        return 'App\Models\Storage';
    }

    public function getExportFile() {
        return '入库表';
    }

    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据

        $result = $this->getFileData();
        return $this->uploadHandle($result);

    }

    public function uploadHandle($data){
//        dd($data);
//        0 => array:2 [
//            "商品编号" => "test01"
//            "数量" => 25
//          ]

        $date = request('uploadDate', Carbon::now());
        $error = false;
        $arrErrors = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($data as $v){
            if (empty($v['商品编号'])) {
                break;
            }
            $value = [
              'good_number' => $v['商品编号']
            ];
            $rules = [
               'good_number' => 'required|string|exists:goods,number'
            ];
            $validator = Validator::make($value,$rules);
            if ($validator->fails()){
                $err = $validator->errors($validator);
                $errTips = '';
                $errorCount ++;
                $error = true;
                foreach ($err->all() as $message){
                  $errTips = $errTips.$message.',';
                }
                $errTips = substr($errTips,0 ,strlen($errTips)-1);
                $v['错误原因'] = $errTips;
                $arrErrors[] = $v;
            } else {
                $successCount++;
                $item = [
                    'good_number' => $v['商品编号'],
                    'good_count' =>(int)$v['数量'],
                    'good_id'  => $this->getIdByNumber($v['商品编号']),
                    'date' => $date,
                ];
                Storage::updateOrCreate(
                    ['date' => $date, 'good_id' => $item['good_id']],
                    $item
                );
            }
        }

        $reTips = '导入完成，其中导入成功' . $successCount . '条';
        if ($error) {
            $reTips = $reTips . ',导入失败' . $errorCount . '条';
            $recs = collect($arrErrors);
            $file = public_path('xls') . '\\' . 'error.xlsx';
            (new FastExcel($recs))->export($file);
            return $this->errorWithInfo($reTips);
        } else {
            return $this->successWithInfo($reTips);
        }

    }

    protected function getIdByNumber($number)
    {
        $id = Good::where('number', $number)->value('id');
        return $id;
    }

}
