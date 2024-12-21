<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Result;
    // 批量删除记录

   //导入数据相关变量
    protected $error = false;
    protected $errorCount = 0;
    protected $successCount = 0;
    protected $arrErrors = [];

    public function deleteAll()
    {
        $request = request();
        $data = $this->deleteByIds($request);
        $model = $this->getModel();
        if ($model::destroy($data['ids'])) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    // 导出所有的内容
    public function exportAll() {

        $this->exportHandle(null, 1);
    }

    // 导出当前指定的页
    public function export()
    {
        $request = request();
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize? $pageSize: 10;
        $page = (int)$request->input('page');
        $page = isset($page) && $page ? $page: 1;
        $this->exportHandle($pageSize, $page);
    }

    public function exportHandle($pageSize, $page)
    {
        // 处理流程，模板方法
        // 1、找出指定的数据
        $lists = $this->queryData($pageSize, $page);
        $data = $lists->toArray();  // 分页内容
        // 内部逻辑处理， 生成表头或者对应的去找关联数据
        $items = $this->generatorData($data);
        // 最后生成电子表格
        $this->generatorXls($items);
    }

    /**
     * 生成xls文件
     */
    public function generatorXls($items)
    {
        $file = $this->getExportFile();
        Excel::create($file, function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }


    protected function getFileData()
    {
        $file = storage_path('app') . '/' . $this->fileUpdate();
        $collection = (new FastExcel())->import($file);
        $data = $collection->toArray();
        return $data;
    }

    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $this->getFileData();
        $data = $this->uploadHandle($result);
        $model = $this->getModel();
        if ($model::insert($data)) {
            return $this->success();
        } else {
            return $this->errorWithInfo('数据导入失败，请检车文件格式是否正确');
        }
    }

    public function uploadHandle($data){
        $lists = [];
        foreach ($data as $v){
            $item = [
                'name' => $v['name'],
                'email' =>$v['email'],
                'role' =>'user',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'password' => bcrypt('123456')
            ];
            $lists[] = $item;
        }
        return $lists;
    }


    public function returnTips()
    {
        $tips = '导入完成，其中，导入成功'.$this->successCount.'条';
        if ($this->error) {
            $tips = $tips.',导入失败'.$this->errorCount.'条';
            $filePath = public_path('xls').'/error.xlsx';
            (new FastExcel(collect($this->arrErrors)))->export($filePath);
            return $this->errorWithInfo($tips);
        } else {
            return $this->successWithInfo($tips);
        }
    }

}
