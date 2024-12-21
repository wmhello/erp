<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodStoreRequest;
use App\Http\Requests\GoodUpdateRequest;
use App\Http\Resources\GoodCollection;
use App\Models\Good;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class GoodController extends Controller implements Model
{
    public function index()
    {
        //
        $pageSize = request('pageSize',10);
        $model = $this->getModel();
        $data = $model::Number()->CateId()->paginate($pageSize);
        return new GoodCollection($data);

    }



    public function store(GoodStoreRequest $request)
    {
        $data = $request->only(['number', 'name', 'rule_color','rule_size', 'remark', 'cate_id', 'cost', 'source', 'img']);
        $model = $this->getModel();
        // 新建商品信息的同时新建库存
        DB::transaction(function () use ($model, $data) {
            $id = $model::insertGetId($data);
            DB::table('stocks')->insert([
                'good_id' => $id,
                'good_number' => $data['number'],
                'count' => 0,
                'remark' => '',
                'created_at' => Carbon::now()
            ]);
        });
        return $this->success();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function show(Good $good)
    {
        //
        //
        $obj = func_get_arg(0);
        return new \App\Http\Resources\Good($obj);

    }




    public function update(GoodUpdateRequest $request, Good $good)
    {
        //
        $data = $request->only(['number', 'name', 'rule_size','rule_color','remark', 'cate_id', 'cost', 'source', 'img']);
        $model = $this->getModel();
        $obj = func_get_arg(1);

        DB::transaction(function () use ($model, $obj, $data) {
            $model::where('id', $obj->id)->update($data);
            $good_number = DB::table('stocks')->where('good_id', $obj->id)->value('good_number');
            if ($good_number !== $data['number']) {
                DB::table('stocks')->where('good_id', $obj->id)->update(['good_number'=>$data['number']]);
            }
        });

        return $this->success();

    }


    public function destroy(Good $good)
    {
        //
        $obj = func_get_arg(0);
        if ($this->deleteHandle($obj->id)){
            return $this->success();
        }
    }

    public function getModel()
    {
        return 'App\Models\Good';
    }

    public function getExportFile() {
        return '商品表';
    }

    public function uploadHandle($data){
        $lists = [];
        //dd($data);
        $messages = [
            'number.required' => '商品编号必须填写',
             'cate_id.required' => '商品种类必须填写',
            'cate_id.exists' => '指定的商品种类无效',
            'cate_id.integer' => '商品种类必须是整形，请查看种类表',
            'number.unique' => '商品编号已经存在,不能重复'
        ];
        $rules =[
            'number' => 'required|unique:goods',
            'cate_id' => 'required|exists:cates,id|integer',
        ];
        $errors = [];
        $isError = false;
        $successCount = 0;
        $errorCount = 0;
        foreach ($data as $v){
            if (empty($v['商品编号'])){
                // 商品编号为空，则退出导入
                break;
            }
            $data = [
                'number' => $v['商品编号'],
                'name' =>$v['商品名称'],
                'img' =>$v['图片'],
                'cate_id' =>$v['种类'],
                'cost' =>$v['成本'],
                'source' =>$v['采购来源'],
                'rule_color' => $v['商品颜色'],
                'rule_size' => $v['商品尺寸'],
                'remark' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $validator = Validator::make($data,$rules,$messages);
            if ($validator->fails()) {
                $isError = true;
                $errorCount ++;
                $error = $validator->errors($validator);
                $errorTips = '';
                //dd($error->first('number'));
                foreach ($error->all() as $message) {
                     // = implode(',', $item);
                    $errorTips = $errorTips.$message.',';
                }
                $errorTips = substr($errorTips,0,strlen($errorTips)-1);
                $v['失败原因'] = $errorTips;
                $errors[] = $v;
                continue;
            }
            $model = $this->getModel();

                // 同时添加商品表和库存表
            DB::transaction(function () use ($model, $data,&$successCount) {
                $id = $model::insertGetId($data);
                $successCount++;
                DB::table('stocks')->insert([
                    'good_id' => $id,
                    'good_number' => $data['number'],
                    'count' => 0,
                    'remark' => '',
                    'created_at' => Carbon::now()
                ]);
            });
        }
        $info = '信息导入成功'.$successCount.'条';
        if ($isError) {
             $info = $info.'，失败'.$errorCount.'条';
            $filePath = public_path('xls').'\\'.'error.xlsx';
            $errors= collect($errors);
            (new FastExcel($errors))->export($filePath);
            return $this->errorWithInfo($info);
        } else {
            return $this->successWithInfo($info);
        }
    }

    public function upload()
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $this->getFileData();  // 数组
        return $this->uploadHandle($result);
    }

    public function deleteAll()
    {
        $request = request();
        $data = $this->deleteByIds($request);
        $arrDelete = $data['ids'];
        foreach($arrDelete as $v) {
            $this->deleteHandle($v);
        }
        return $this->success();
    }

    public function getInfo()
    {
        $data = DB::table('goods')
            ->select(DB::raw("id, number,name,concat(number,'(',name,')') as tips"))
            ->orderby('id')
            ->get();
        return $this->successWithData($data);
    }

    protected function deleteHandle($id) {
       DB::transaction(function () use ($id){
            DB::table('goods')->where('id', $id)->delete();
            DB::table('sells')->where('good_id', $id)->delete();
            DB::table('storages')->where('good_id', $id)->delete();
            DB::table('configs')->where('good_id', $id)->delete();
            DB::table('purchases')->where('good_id', $id)->delete();
            DB::table('stocks')->where('good_id', $id)->delete();
      });
       return true;
    }

}
