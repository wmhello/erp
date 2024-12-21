<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigStoreRequest;
use App\Http\Requests\ConfigUpdateRequest;
use App\Http\Resources\ConfigCollection;
use App\Models\Config;
use App\Models\Good;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Pagination\LengthAwarePaginator;

class ConfigController extends Controller implements  Model
{

    public function index(Request $request)
    {
        //
        $pageSize = request('pageSize',10);
        $page = request('page', 1);
        $number = request('sub_number', null);
        $model = $this->getModel();

        if ($number) {  // 有erp编号的时候  利用子查询，进行各种过滤
            //dd($number);
            $subQuery = Config::select(DB::raw(' min(configs.id) as id,sub_number,group_concat(good_number) as numbers, group_concat(count) as counts, min(configs.remark) as remark,GROUP_CONCAT(good_id) as good_ids,GROUP_CONCAT(concat(goods.number,\'(\', IFNULL(goods.`name`,\'\'),\')\',\'×\',configs.count)) as tips'))
                ->join('goods', 'goods.id', '=', 'configs.good_id')
                ->groupBy('sub_number');
            $data = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
                ->where('sub.numbers', 'like', '%'.$number.'%')
                ->paginate($pageSize);
            //$data = $query->mergeBindings($subQuery->getQuery());
        } else {


        $data = Config::select(DB::raw(' min(configs.id) as id,sub_number,group_concat(good_number) as numbers, group_concat(count) as counts, min(configs.remark) as remark,GROUP_CONCAT(good_id) as good_ids,GROUP_CONCAT(concat(goods.number,\'(\', IFNULL(goods.`name`,\'\'),\')\',\'×\',configs.count)) as tips'))
            ->join('goods', 'goods.id', '=', 'configs.good_id')
            ->groupBy('sub_number')
            ->when($number, function($query) use ($number){
                return $query->having("good_number", "like", '%'.$number.'%');
            })
//            ->get();
//        $arrayCaclData = $data->toArray();
////        dd($arrayCaclData);
//        //dd($data);
////        if ($number) {
////            $data = $data->filter(function ($value, $key) use ($number) {
////                return strstr($value->numbers, $number)>=0;
////            });
////        }
//
//
//        $prePage = $pageSize;
//        $offset = ($page*$prePage) - $prePage;
//        $data =  new LengthAwarePaginator(array_slice($arrayCaclData, $offset, $prePage, true), count($arrayCaclData), $prePage,
//            $page);

         ->paginate($pageSize);
        }
        return new ConfigCollection($data);

    }



    public function store(ConfigStoreRequest $request)
    {
        $data = $request->all();
        // 前端传来的每一个配对项
        $items = json_decode($data['result'], true);
        $model = $this->getModel();
        foreach ($items as $v){
            $item = [
              'sub_number' => $data['sub_number'],
              'remark' => $data['remark'],
              'good_id' => $v['id'],
              'good_number' => $v['number'],
              'count' => $v['count']
            ];
            $model::create($item);
        }
        return $this->success();

//        if ($model::create($data)) {
//            return $this->success();
//        } else  {
//            return $this->error();
//        }
    }

    public function show(Config $config)
    {
        //
        $obj = func_get_arg(0);
        $sub_number = $config->sub_number;
        $data = DB::table('configs')
            ->select(DB::raw(' min(configs.id) as id,sub_number,group_concat(good_number) as numbers, group_concat(count) as counts, min(configs.remark) as remark,GROUP_CONCAT(good_id) as good_ids,GROUP_CONCAT(concat(goods.number,\'(\', goods.`name`,\')\',\'×\',configs.count)) as tips'))
            ->join('goods', 'goods.id', '=', 'configs.good_id')
            ->groupby('sub_number')
            ->having('sub_number',$sub_number)
            ->get();
        return new \App\Http\Resources\Config($data[0]);

    }




    public function update(ConfigUpdateRequest $request, Config $config)
    {
        //
        $data = $request->all();
        $model = $this->getModel();
        $sub_munber = $data['sub_number'];
        $id = $data['id'];
        $oldData = DB::table('configs')
                  ->where('sub_number', $sub_munber)
                  ->pluck('good_id');
        $oldIds = $oldData->toArray();
        $newData = json_decode($data['result'],true);
        $newIds = array_column($newData, 'id');
       //var_dump($newIds);
        // 处理两个数组的内容，老数组代表已经存在的项目，新数组代表前端配置好的的项目
        // 老数组有 但新数组没有的内容 代表着要被删除
        $arrDel = array_diff($oldIds, $newIds);
        foreach ($arrDel as $delId){
            DB::table('configs')
                ->where('sub_number', $sub_munber)
                ->where('good_id', $delId)
                ->delete();
        }
        // 新数组有 但老数组没有的 要新增
        $arrNew = array_diff($newIds, $oldIds);
        foreach ($arrNew as $new){
           foreach ($newData as $key => $item){
               if ($item['id'] ===$new){
                   DB::table('configs')
                       ->insert([
                           'sub_number' => $sub_munber,
                           'remark' => $data['remark'],
                           'good_id' => $item['id'],
                           'good_number' => $item['number'],
                           'count' => $item['count']
                       ]);
                   break;
               }
           }
        }

        // 如果都有的 要被修改
        $arrUpdate = array_intersect($oldIds,$newIds);
        foreach ($arrUpdate  as $update){
            foreach ($newData as $key => $item){
                if ($item['id'] ===$update){
                    DB::table('configs')
                        ->where('sub_number', $sub_munber)
                        ->where('good_id', $update)
                        ->update([
                            'sub_number' => $sub_munber,
                            'remark' => $data['remark'],
                            'good_id' => $item['id'],
                            'good_number' => $item['number'],
                            'count' => $item['count']
                        ]);
                    break;
                }
            }
        }
            return $this->success();

    }


    public function destroy(Config $config)
    {
        //
        $sub_number =$config->sub_number;
        $obj = func_get_arg(0);
        DB::table('configs')
            ->where('sub_number', $sub_number)
            ->delete();
            return $this->success();

    }

    public function getModel()
    {
        return 'App\Models\Config';
    }

    public function getExportFile() {
        return '配置表';
    }

    public function test()
    {
       $data = DB::table('configs')
            ->select(DB::raw(' min(id) as id,sub_number,group_concat(good_number) as numbers, group_concat(count) as counts, min(remark) as remarks'))
            ->groupby('sub_number')
            ->paginate(10);
        return $this->successWithData($data);
    }

    public function deleteAll()
    {
        $request = request();
        $data = $this->deleteByIds($request);
        $arrIds = $data['ids'];
        $arrData = DB::table('configs')
                     ->whereIn('id', $arrIds)
                     ->pluck('sub_number');
        $arrDelete = $arrData->toArray();
        foreach ($arrDelete as $item){
            DB::table('configs')
                ->where('sub_number', $item)
                ->delete();
        }
        return $this->success();
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
        $error = false;  // 是否导入失败
        $tipsError = []; // 导入失败的数据
        $succcessCount = 0;  // 成功的记录数
        $errorCount = 0;  // 失败的记录数

        foreach($data as $key=>$item) {
            // 遇到空的内容则自动终止导入数据
            if (empty($item['线上编号'])) {

                break;
            }
            //dd($key);
            // 校验导入的数据是否合法
            $value = [
                'sub_number' => $item['线上编号'],
                'good_number' => $item['erp编号'],
                'count' => $item['数量'],
            ];
            $rules = [
                'sub_number' => 'required|string',
                'good_number' => 'required|exists:goods,number',
                'count' => 'required|integer'
            ];
            $messages = [
                'sub_number.required' => '配对编号必须填写',
                'good_number.required' => '商品编号必须填写',
                'good_number.exists' => '商品编号不存在，无法导入',
                'sub_number.string' => '配对编号必须是字符串',
                'count.required' => '商品数量必须填写',
                'count.integer' => '商品数量格式不对,必须是整数'
            ];

            $validator = Validator::make($value, $rules, $messages);

            if ($validator->fails()) {
                // 数据导入不合法，提示相关记录并增加出租的条数
                $error = true;
                $arrErrors = $validator->errors($validator);
                $tips = '';
                foreach ($arrErrors->all() as $message) {
                    //
                    $tips = $tips . $message . ',';
                }
                $tips = substr($tips, 0, strlen($tips) - 1);
                $err = $item;
                $err['出错原因'] = $tips;
                $tipsError[] = $err;
                $errorCount++;
                continue;
            } else {
                // 数据检测合法，则直接插入数据到配对表
                $value['good_id'] = $this->getIdByNumber($value['good_number']);
                Config::create($value);
                $succcessCount++;
            }
        }
            // 根据结果，做不同的返回
            $reTips = '导入完成，其中导入成功' . $succcessCount . '条';
            if ($error) {
                $reTips = $reTips . ',导入失败' . $errorCount . '条';
                $recs = collect($tipsError);
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

    public function getAll()
    {
        $data = DB::table('configs')
            ->select(DB::raw(' min(configs.id) as id,sub_number, min(configs.remark) as remark'))
            ->groupby('sub_number')
            ->get();
        return $this->successWithData($data);
    }

}
