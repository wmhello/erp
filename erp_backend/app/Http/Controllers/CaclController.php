<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaclStoreRequest;
use App\Http\Requests\CaclUpdateRequest;
use App\Http\Resources\CaclCollection;
use App\Http\Resources\Purchase;
use App\Models\Cacl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class CaclController extends Controller implements Model
{
   public $sellData = [];

    public function index(Request $request)
    {
        //
        $calDay = (int)DB::table('params')->where('name','avg_sell')->value('value');
        $goodDay =(int)DB::table('params')->where('name','good_day')->value('value');
        $storkDay =(int)DB::table('params')->where('name','stork_day')->value('value');
        $ignoreCount = (int)DB::table('params')->where('name','ignore_count')->value('value');
        $today = Carbon::now();
        $goodNumber = $request->input('good_number');
        $cateId =(int)$request->input('cate_id');
        $startDay = $today->subDay($calDay-1);  // 开始计算的天数  到 今天  ，刚好是要计算的天数（$calDay）

        $pageSize = request('pageSize',10);
        // 获取最近一段时间有销售的商品类型信息和销售的记录以及平均销售记录
        $testData = DB::select("select stocks.*, goods.name, goods.cate_id, c.good_count, c.agv_count from stocks join goods on stocks.good_id = goods.id  right join (select min(temp.good_id) as good_id, temp.good_number, sum(good_count) as good_count, sum(good_count)/:calDay as agv_count from
                            (select * from sells where (date>=:startDay and date<=now() ) and good_count < :ignoreCount ) temp  group by temp.good_number) c on c.good_id = stocks.good_id;", ['calDay'=>$calDay,'startDay'=>$startDay, 'ignoreCount' =>  $ignoreCount]);

        // 过滤产品种类
        if ($cateId) {
           foreach ($testData as $key => $value){
               if ($value->cate_id!==$cateId){
                   unset($testData[$key]);
               }
           }
        }
        // 过滤编号
        if ($goodNumber) {
           foreach ($testData as $key => $value){
               if (strpos($value->good_number,$goodNumber) === false){
                   unset($testData[$key]);
               }
           }
        }

        $testCollection = Collect($testData);
        $caclData= $testCollection->map(function ($item, $key) use($goodDay,$storkDay){

            $item->jj_count = round($item->agv_count * $goodDay);  //警戒数量,每个商品根据销售得到的警戒数量
            $item->kc_count = round($item->agv_count * $storkDay); // 需要的库存数量
            $item->yj_day = floor($item->count / $item->agv_count);  // 预计剩余能够销售的天数
            $item->good_count = round($item->good_count,1);  // 转换为数字类型
            $item->agv_count = round($item->agv_count,1);
            return $item;
        });
        $arrayCaclData = $caclData->toArray();
        $page = $request->page?:1;
        $prePage = $pageSize;
        $offset = ($page*$prePage) - $prePage;
        $data =  new LengthAwarePaginator(array_slice($arrayCaclData, $offset, $prePage, true), count($arrayCaclData), $prePage,
            $page);
        return new CaclCollection($data);

    }



    public function store(CaclStoreRequest $request)
    {
        $data = $request->all();
        $model = $this->getModel();
        if ($model::create($data)) {
            return $this->success();
        } else  {
            return $this->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function show(Cacl $cacl)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Cacl($obj);

    }




    public function update(CaclUpdateRequest $request, Cacl $cacl)
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


    public function destroy(Cacl $cacl)
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
        return 'App\Models\Cacl';
    }


    public function cacl(Request $request)
    {
        $calDay = (int)DB::table('params')->where('name','avg_sell')->value('value');
        $goodDay =(int)DB::table('params')->where('name','good_day')->value('value');
        $storkDay =(int)DB::table('params')->where('name','stork_day')->value('value');
        $ignoreCount = (int)DB::table('params')->where('name','ignore_count')->value('value');
        $today = Carbon::now();
        $startDay = $today->subDay($calDay-1);  // 开始计算的天数  到 今天  ，刚好是要计算的天数（$calDay）

//        $stocksData = DB::select('select stocks.*, goods.name from stocks join goods on stocks.good_id = goods.id');
//        //dd($stocksData);
//        // 计算机最近一个月或者执行的天数的销售物品和数量总和，并求出了平均销售天数  利用子查询先求出指定的销售记录，然后进行根据编号分组
//        $sellsData = DB::select("select min(temp.good_id) as good_id, temp.good_number, sum(good_count) as good_count, ceil(sum(good_count)/:calDay) as agv_count from
//                            (select * from sells where date>=:startDay and date<=now()) temp
//                            group by temp.good_number;",['calDay'=>$calDay,'startDay'=>$startDay ]);
//        $this->sellData = $sellsData;
        // 返回的数据
//        array:2 [
//        0 => {#398
//                    +"good_number": "P689"
//                    +"good_count": "58"
//                    +"agv_count": "2"
//              }
//              1 => {#385
//                    +"good_number": "P930-heimoshui"
//                    +"good_count":  "13"
//                    +"agv_count":   "1"
//              }
//            ];
//        foreach($data as $key=>$value){
//
//        }

        // 获取最近一段时间有销售的商品类型信息和销售的记录以及平均销售记录
        $testData = DB::select("select stocks.*, goods.name, c.good_count, c.agv_count from stocks join goods on stocks.good_id = goods.id  right join (select min(temp.good_id) as good_id, temp.good_number, sum(good_count) as good_count, round(sum(good_count)/:calDay) as agv_count from
                            (select * from sells where date>=:startDay and date<=now() and good_count < :ignoreCount) temp  group by temp.good_number) c on c.good_id = stocks.good_id;", ['calDay'=>$calDay,'startDay'=>$startDay, 'ignoreCount'=>$ignoreCount]);



        $testCollection = Collect($testData);
        $caclData= $testCollection->map(function ($item, $key) use($goodDay,$storkDay){
            $item->good_count = (int)$item->good_count;  // 转换为数字类型
            $item->agv_count = (int)$item->agv_count;
            $item->jj_count = $item->agv_count * $goodDay;  //警戒数量,每个商品根据销售得到的警戒数量
            $item->kc_count = $item->agv_count * $storkDay; // 需要的库存数量
            $item->yj_day = floor($item->count / $item->agv_count);  // 预计剩余能够销售的天数
            return $item;
        });
        $arrayCaclData = $caclData->toArray();
        $page = $request->page?:1;
        $prePage = 1;
        $offset = ($page*$prePage) - $prePage;
        $data =  new LengthAwarePaginator(array_slice($arrayCaclData, $offset, $prePage, true), count($arrayCaclData), $prePage,
            $page);
        return new CaclCollection($data);
//        Cache::set('caclData', $caclData);
//
//        return $this->successWithData($caclData);
//        array:2 [
//                0 => {#3006
//                    +"id": 1
//                    +"good_id": 1
//                    +"good_number": "P689"
//                    +"count": 112
//                    +"remark": ""
//                    +"deleted_at": "2018-12-03 19:35:15"
//                    +"created_at": "2018-12-03 19:35:15"
//                    +"updated_at": "2018-12-03 19:35:15"
//                    +"name": "水彩笔"
//                    +"good_count": "58"
//                    +"agv_count": "2"
//              },
//              1 => {#3007
//                    +"id": 2
//                    +"good_id": 2
//                    +"good_number": "P930-heimoshui"
//                    +"count": 114
//                    +"remark": null
//                    +"deleted_at": "2018-12-03 19:35:15"
//                    +"created_at": "2018-12-03 19:35:15"
//                    +"updated_at": "2018-12-03 19:35:15"
//                    +"name": "黑墨水"
//                    +"good_count": "13"
//                    +"agv_count": "1"
//              }
//        ]

        
//        $sellsId = array_pluck($sellsData,'good_id'); //获取这段时间销售的商品id
//        //$data1 = DB::select('select stocks.*, goods.name from stocks join goods on stocks.good_id = goods.id where stocks.good_id IN  (1,2)');
////         $data1 =  DB::table('stocks')
////                     ->select(['stocks.*', 'goods.name'])
////                     ->join('goods', 'stocks.good_id', '=', 'goods.id')
////                     ->whereIn('stocks.good_id', $sellsId)
////                     ->get();
//         // 获取最近一段有销售记录的物品的库存单子
//        $array = array_where($stocksData, function ($value, $key) use ($sellsId){
//            return in_array($value->good_id, $sellsId);
//        });
//
//        //$array返回最近一段时间有销售记录的物品的库存信息，和上面一个sql达到一样的效果
//        foreach($array as $key=>$item){
//           $agvCount = $this->getSellById($item->good_id);  // 得出当前的商品近一段时间的平均销售
//           dump($agvCount);
//        }

        // 每条记录解释  count（'库存'）  good_count（单位时间内的销售数量） agv_count(平均每天的销售数量)
       //
    }

   public function getSellById($id)
   {

        $array = array_where($this->sellData, function ($value, $key) use ($id){
            return ($value->good_id === $id);
        });

        // 重建数组索引
       $array = array_merge($array, []);
        return $array[0]->agv_count;

    }

    public function getExportFile() {
        return '采购表.xlsx';
    }

    public function export()
    {
        // 获取各种参数，利用这些参数计算统计，导出数据
        $calDay = (int)DB::table('params')->where('name','avg_sell')->value('value');
        $goodDay =(int)DB::table('params')->where('name','good_day')->value('value');
        $storkDay =(int)DB::table('params')->where('name','stork_day')->value('value');
        $minCount =(int)DB::table('params')->where('name','min_count')->value('value');
        $minStock =(int)DB::table('params')->where('name','min_stock')->value('value');

        $today = Carbon::now();
        $startDay = $today->subDay($calDay-1);  // 开始计算的天数  到 今天  ，刚好是要计算的天数（$calDay）

        // 获取最近一段时间有销售的商品类型信息和销售的记录以及平均销售记录
        $testData = DB::select("select stocks.*, goods.name,goods.source,goods.rule_color, goods.rule_size,goods.remark as good_remark, c.good_count, c.agv_count from stocks join goods on stocks.good_id = goods.id  right join (select min(temp.good_id) as good_id, temp.good_number, sum(good_count) as good_count, sum(good_count)/:calDay as agv_count from
                            (select * from sells where date>=:startDay and date<=now()) temp  group by temp.good_number) c on c.good_id = stocks.good_id;", ['calDay'=>$calDay,'startDay'=>$startDay ]);


        //dd($testData);
        $testCollection = Collect($testData);

        $lists = \App\Models\Purchase::get(['good_id','good_count']);
        $caclData= $testCollection->map(function ($item, $key) use($goodDay,$storkDay, $lists, $minCount, $minStock){

            $item->jj_count = round($item->agv_count * $goodDay);  //警戒数量,每个商品根据销售得到的警戒数量
            $item->kc_count = round($item->agv_count * $storkDay); // 需要的库存数量
            $item->yj_day = floor($item->count / $item->agv_count);  // 预计剩余能够销售的天数
            $item->good_count = round($item->good_count,1);  // 转换为数字类型
            $item->agv_count = round($item->agv_count,1);
            $item->is_purchase = false; // 是否需要采购
            $item->purchase_count = 0; // 采购数量
            // 获取在途采购信息
            $arr = $lists->whereIn('good_id', $item->good_id)->toArray();
            if (count($arr)){
             // 有在途采购信息
                // 采购量 = 累计需要采购量 + 多余库存量 - 现在的库存量 - 在途数量
                $arrPurchase = array_merge($arr,[]); // 压缩内容，合并成数组，第一个元素就是本商品的采购量
                if ($item->count + $arrPurchase[0]['good_count'] <$item->jj_count) {
                    $item->is_purchase = true;
                    $item->purchase_count = (int)($item->jj_count + $item->kc_count - $item->count - $arrPurchase[0]['good_count']);
                    if ($item->purchase_count<=$minCount) {
                        $item->purchase_count = $minCount;
                    }
                }


            } else {
             // 没有在途采购数量
                if ($item->count<$item->jj_count) {
                    // 计算建议采购值
                    $item->is_purchase = true;
                   $item->purchase_count = (int)($item->jj_count + $item->kc_count - $item->count);
                   // 处理最低采购量
                    if ($item->purchase_count<=$minCount) {
                        $item->purchase_count = $minCount;
                    }
                }
                // 第二种处理方式（库存为零，且近30天的购买数量小于等于3的情况下，特殊情况的处理）
                if ($item->count === 0 && $item->good_count <=3){
                    $item->is_purchase = true;
                    $item->purchase_count = $minStock;
                }
            }
            return $item;
        });
         // 过滤 如果需要采购的显示出来
        $filtered = $caclData->filter(function ($value, $key) {
            return $value->is_purchase;
        });

        // 生成数据

         if ($filtered->count()===0) {
             return $this->errorWithInfo('没有需要采购的商品');
         }
         $result = $filtered->map(function ($value, $key) {
             $item= [
                '商品编号' => $value->good_number,
                 '商品名称' => $value->name,
                 '商品数量(件)' => $value->purchase_count,
                 '商品规格1（如：颜色）' => $value->rule_color,
                 '商品规格2（如：尺码）' => $value->rule_size,
                 '1688商品链接/1688商品id'=>$value->source,
                 '备注' => $value->good_remark
             ];
             return $item;
         });

        $file = $this->getExportFile();
        $filePath = public_path('xls').'\\'.$file;
        (new FastExcel($result))->export($filePath);
    }

}
