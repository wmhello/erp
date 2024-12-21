<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackagePost;
use App\Http\Requests\UpdatePackagePut;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

    }

    public function packages(Request $request, $orderId)
    {
       $orderId = intval($orderId);
//       $data = DB::select("
//           select details.*, product_name, product_number,logistics_number, logistics_date from details
//               inner join products on details.product_id = products.id
//               inner join packages on details.package_id = packages.id
//               where details.order_id = :orderId
//           ", ['orderId' => $orderId]);
//           dd($data);
        $data =  DB::select("
             select package_id, logistics_number, logistics_date ,group_concat(concat(product_name, '*', product_amount)) as info, remark from 
             (select details.package_id,details.order_id,details.product_id, details.product_amount,packages.remark, products.product_name, products.product_number,logistics_number, logistics_date from details  
               inner join products on details.product_id = products.id 
               inner join packages on details.package_id = packages.id 
               where details.order_id = :orderId ) as sub 
               group by(package_id) order by package_id desc ", ["orderId" => $orderId]);
        return $this->successWithData($data);

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
    public function store(StorePackagePost $request)
    {
        //
       $data  = $request->only(['logistics_number','logistics_date', 'items','remark']);
       // 格式化日期
       $data['logistics_date'] = Carbon::createFromTimestamp($data['logistics_date']);
       // 增加的详细信息放入details表
       $details = $data['items'];
       $data['created_at'] = Carbon::now();
       $data['updated_at'] = Carbon::now();
       unset($data['items']);

      // return $this->successWithData($details);
       // 增加包裹记录的同时，添加详细的产品信息并扣除剩余数量，并计算该订单状态
//        $packageId = Package::insertGetId($data);
//        for($item = 0, $size = count($details); $item < $size; $item++){
//            $details[$item]['package_id'] = $packageId;
//        }
//        foreach ($details as $detail){
//            // 产品表中更新剩余记录
//            //list($orderId,$productAmount,$productId,$packageId) = $detail;
//            $amount = Product::where('id', $detail['product_id'])->value('remain_amount');
//            Product::where('id',  $detail['product_id'])->update([
//                'remain_amount' => $amount -  $detail['product_amount']
//            ]);
//        }
//        dd($details);
//        Detail::create($details);

        DB::transaction(function() use($data,$details){
           $packageId = Package::insertGetId($data);
           $items = $details;
           for($item = 0, $size = count($details); $item < $size; $item++){
               $items[$item]['package_id'] = $packageId;
           }
           foreach ($items as $detail){
               // 产品表中更新剩余记录
                //list($orderId,$productAmount,$productId,$packageId) = $detail;
               $amount = Product::where('id', $detail['product_id'])->value('remain_amount');
               Product::where('id',  $detail['product_id'])->update([
                  'remain_amount' => $amount -  $detail['product_amount']
               ]);
               Detail::create($detail);
           }
           $orderId = $items[0]['order_id'];
           $this->updateOrderStatus($orderId);
       }, 5);
       return $this->success();
    }


    /**
     * @param $orderId 商品订单
     * 根据产品数量，更新订单的状态
     */
    protected function updateOrderStatus($orderId){
        $orderAmount = Product::where('order_id', $orderId) ->sum('product_amount');
        $orderRemain = Product::where('order_id', $orderId) ->sum('remain_amount');
        if ($orderAmount === $orderRemain) {
            // 表示没有任何一件商品送出
            Order::where('id',$orderId)->update(['status' => 1]);
        } else if ($orderRemain === 0) {
            // 表示数量没有剩余，已经发送完
            Order::where('id',$orderId)->update(['status' => 3]);
        } else {
            Order::where('id',$orderId)->update(['status' => 2]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
        return new \App\Http\Resources\Package($package);
    }
    public function showPackage($id)
    {
        //
        $data = DB::select('
        SELECT 
            details.id,
            orders.order_number,
            orders.order_time,
            orders.merchant_number,
            orders.merchant_name,
            products.product_number,
            products.product_name,
            products.product_img,
            details.product_amount,
            packages.logistics_number,
            packages.logistics_date
            FROM 
            details 
            inner join packages on packages.id = details.package_id 
            inner join products on products.id = details.product_id
            inner join orders on orders.id = details.order_id
            where package_id = :package_id
', ['package_id' => $id]);
      return $this->successWithData($data);
    }
    public function info($packageId)
    {
        // 指定包裹对应的产品信息
        dd($packageId);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackagePut $request, Package $package)
    {
        //
        $data  = ['logistics_number','logistics_date', 'remark'];
        $data['logistics_date'] = Carbon::createFromTimestamp($data['logistics_date']);
        if (Package::where('id', $package->id)->save($data)) {
            return $this->success();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        // 删除内容会影响到特定的指定的detail（明细表）

        DB::transaction(function() use ($package){
          $packageTd = $package->id;
          if ($package->delete()){
              // 删除details，并在product表的剩余记录上增加
               $details  = Detail::where('package_id', $packageTd)->get();
               Detail::where('package_id', $packageTd)->delete();
               $details->each(function($item){
                   $remainAmount = Product::where('id',$item->product_id)->value('remain_amount');
                   Product::where('id', $item->product_id)->update(['remain_amount' => $remainAmount + $item->product_amount]);
                   // 找到该产品对应的订单id，然后计算该id对应的所有产品的种类数量和剩余数量，如果相等了，就说明没有发送，如果不等就说明已经发送，但未完
                   // 根据产品表，然后计算出order表的状态
                   $orderId = Product::where('id',$item->product_id)->value('order_id');
                   $orderAmount = Product::where('order_id', $orderId) ->sum('product_amount');
                   $orderRemain = Product::where('order_id', $orderId) ->sum('remain_amount');
                   if ($orderAmount === $orderRemain) {
                       // 表示没有任何一件商品送出
                       Order::where('id',$orderId)->update(['status' => 1]);
                   } else {
                       // 表示已经发送，但未完
                       Order::where('id',$orderId)->update(['status' => 2]);
                   }
               }) ;
          }
          $count = Detail::where('package_id', $package)->count();
          // 如果该包裹下没有任何产品，删除该包裹
          if ($count === 0){
              Package::where('id', $packageTd)->delete();
          }
        });

    }

    public function remark(Request $request,$id)
    {
        $remark = $request->input('remark');
        Package::where('id', $id)->update(
            [
                'remark' => $remark
            ]
        );
        return $this->success();
        
    }
}
