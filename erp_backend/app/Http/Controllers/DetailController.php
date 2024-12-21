<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetailPost;
use App\Http\Requests\UpdateDetailPut;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreDetailPost $request)
    {
        //
        $data = $request->input(['package_id', 'product_id', 'product_amount', 'remark', 'order_id']);
        DB::transction(function() use ($data){
            if (Detail::create($data)){
                $remainAmount = Product::where('id', $data['product_id'])->value('remain_amount');
                Product::where('id', $data['product_id'])->update([
                      'remain_amount' => $remainAmount -$data['product_amount']
                ]);
                return $this->success();
            }
        });

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function show(Detail $detail)
    {
        //
        return new \App\Http\Resources\Detail($detail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Detail $detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetailPut $request, Detail $detail)
    {
        //
        $data = $request->input(['package_id', 'product_id', 'product_amount', 'remark', 'order_id']);
        if (Detail::where('id', $detail->id)->update($data)){
            return $this->success();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detail $detail)
    {
        //
        DB::transaction(function () use ($detail){
           $detailId = $detail->id;
           $productId = $detail->product_id;  // 产品id
           $orderId = $detail->order_id;      // 订单id
           $packageId = $detail->package_id;  //  包裹id
            $productAmount = $detail->product_amount ;
            $detail->delete();
            // 处理产品
            $remain = Product::where('id', $productId)->value('remain_amount');
            Product::where('id', $productId)->update([
                'remain_amount' => $remain + $productAmount
            ]);
            // 处理包裹  一个包裹，有多条内容， 如果没有多条记录，则把对应的包裹删除
            $count = Detail::where('package_id', $packageId)->count();
            if ($count === 0) {
                Package::where('id', $packageId)->delete();
            }

            // 处理订单，
            $orderAmount = Product::where('order_id', $orderId) ->sum('product_amount');
            $orderRemain = Product::where('order_id', $orderId) ->sum('remain_amount');
            if ($orderAmount === $orderRemain) {
                // 表示没有任何一件商品送出
                Order::where('id',$orderId)->update(['status' => 1]);
            } else {
                // 表示已经发送，但未完
                Order::where('id',$orderId)->update(['status' => 2]);
            }
        });
    }
}
