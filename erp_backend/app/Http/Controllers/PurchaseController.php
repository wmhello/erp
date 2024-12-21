<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchasesUpdateRequest;
use App\Http\Resources\PurchaseCollection;
use App\Models\Good;
use App\Models\Purchase;
use App\Models\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $cateId = request('cate_id', null);
        $data = $model::Number()
                ->Date()
                ->CateId()
                ->select('purchases.*')
                ->orderBy('purchases.id', 'desc')->paginate($pageSize);
        return new PurchaseCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //

        return new \App\Http\Resources\Purchase($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(PurchasesUpdateRequest $request, Purchase $purchase)
    {
        //

        $data = $request->only(['good_id', 'good_number', 'good_count', 'remark', 'date']);
        if (Purchase::where('id', $purchase->id)->update($data)){
            return $this->success();
        } else {
            return $this->errorWithInfo('数据更新失败');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
        if ($purchase->delete()){
            return $this->success();
        } else {
            return $this->errorWithInfo('删除采购信息失败');
        }
    }

    public function getModel()
    {
        return 'App\Models\Purchase';
    }

    public function getExportFile() {
        return '采购表.xlsx';
    }

    public function export()
    {
        $list = collect([
            [ 'id' => 1, 'name' => 'Jane' ],
            [ 'id' => 2, 'name' => 'John' ],
        ]);
        $file = $this->getExportFile();
        $filePath = public_path('xls').'\\'.$file;
        (new FastExcel($list))->export($filePath);
    }

    public function getInfo()
    {
        $lists = Purchase::get(['good_id','good_count']);
        return $this->successWithData($lists);
    }

    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据

        $result = $this->getFileData();
        return $this->uploadHandle($result);
//        if (Purchase::insert($data)) {
//            return $this->success();
//        } else {
//            return $this->errorWithInfo('数据导入失败，请检车文件格式是否正确');
//        }
    }

    public function uploadHandle($data){
        $error = false;  // 是否导入失败
        $tipsError = []; // 导入失败的数据
        $succcessCount = 0;  // 成功的记录数
        $errorCount = 0;  // 失败的记录数

        $lists = [];
        $date = request('uploadDate', Carbon::now());
        $rules = [
            'good_number' => 'required|exists:goods,number'
        ];
        $messages = [
            'good_number.exists' => '商品编号不存在'
        ];
//        '商品编号' => $value->good_number,
//                 '商品名称' => $value->name,
//                 '商品数量(件)' => $value->purchase_count,
//                 '商品规格1（如：颜色）' => $value->rule_color,
//                 '商品规格2（如：尺码）' => $value->rule_size,
//                 '1688商品链接/1688商品id'=>$value->source,
//                 '备注' => $value->good_remark

        foreach ($data as $v){
            $item = [
                'good_number' => $v['商品编号'],
                'good_count' =>(int)$v['商品数量(件)'],
                'good_id'  => $this->getIdByNumber($v['商品编号']),
                'date' => $date,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $validator = Validator::make($item,$rules,$messages);
            if ($validator->fails()){
                // 数据导入不合法，提示相关记录并增加出租的条数
                $error = true;
                $arrErrors = $validator->errors($validator);
                $tips = '';
                foreach ($arrErrors->all() as $message) {
                    //
                    $tips .= ','. $message;
                }
                $tips = substr($tips, 1);
                $err = $v;
                $err['出错原因'] = $tips;
                $tipsError[] = $err;
                $errorCount++;
                continue;
            } else {
              Purchase::create($item);
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

    public function deleteAll()
    {
        if (Purchase::truncate()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function importStorage()
    {
        $ids = request('ids');
        $data = Purchase::whereIn('id', $ids)->get(['good_id', 'good_number', 'good_count']);

        $result = $data->map(function($item, $key) {
                $item->date = Carbon::now();
                $item->created_at = Carbon::now();
                $item->updated_at = Carbon::now();
                return $item;
        });
        $result = $result->toArray();
        try {
            DB::transaction(function () use ($result, $ids) {
                Storage::insert($result);
                //Purchase::truncate();
                Purchase::destroy($ids);
            });
            return $this->success();
        } catch (\Exception $e) {
            return $this->errorWithInfo('采购信息入库失败');
        }
    }

    public function test() {
         $request = request();
         $data = [
           [
             'id' => 1,
             'name' => '张三',
             'age' => 20
           ],
           [
             'id' => 2,
             'name' => '李四',
             'age' => 15
           ],
           [
             'id' => 3,
             'name' => '王五',
             'age' => 28
           ],
         ];
         return response()->json([
           'data' => $data,
           'state' => 'success',
           'state_code' => 200
         ], 200);
        // return $this->error();
    }
}
