<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderPost;
use App\Http\Requests\UpdateOrderPut;
use App\Http\Resources\OrderCollection;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $pageSize = $request->input('pageSize', 10);
        $data = Order::OrderNumber()->Status()->MerchantNumber()->MerchantName()->latest()->paginate($pageSize);
        return new OrderCollection($data);
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
    public function store(StoreOrderPost $request)
    {
        //
        $data = $request->only(['order_number', 'merchant_number', 'merchant_name','order_time','status', 'remark']);
        $timeStamp = $data['order_time'];
        $data['order_time'] = Carbon::createFromTimestamp($timeStamp);
        if (Order::create($data)) {
            return $this->success();
        } else {
            return $this->error();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderPut $request, Order $order)
    {
        //
        $data = $request->only(['order_number', 'merchant_number', 'merchant_name','order_time','status', 'remark']);
        $timeStamp = $data['order_time'];
        $data['order_time'] = Carbon::createFromTimestamp($timeStamp);
        if (Order::where('id', $order->id)->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //删除订单对应的商品信息以及包裹信息  保证数据完整性

        $orderId = $order->id;
        DB::transaction(function() use($orderId, $order){
           $order->delete();
           Product::where('order_id', $orderId)->delete();
           $arrPackage = Detail::where('order_id',$orderId)->pluck('package_id');
           $arrPackage = array_unique($arrPackage->toArray());
           Detail::where('order_id',$orderId)->delete();
           Package::whereIn('id', $arrPackage)->delete();
         });
        return $this->success();
    }

    public function products($id)
    {

        $data = Product::where('order_id', $id)->get();
        if (is_object($data)) {
            return new ResourceCollection($data);
        } else {
            return $this->error();
        }
    }
    public function enable($id)
    {

        $data = Product::where('order_id', $id)->where('remain_amount', ">", 0)->get();
        if (is_object($data)) {
            return $this->successWithData($data->toArray());
        } else {
            return $this->error();
        }
    }

    public function summary($id)
    {
        $data = Product::select(DB::raw('count(product_number) as product_count, sum(product_amount) as sum_amount, sum(remain_amount) as sum_remain'))->where('order_id', $id)->get();
        return $this->successWithData($data->toArray()[0]);
    }
}
