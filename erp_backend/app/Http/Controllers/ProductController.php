<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductPost;
use App\Http\Requests\UpdateProductPut;
use App\Http\Resources\ProductCollection;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
        $id = $request->input('id');
        $data = Product::where('order_id', $id) ->paginate($pageSize);
        return new ProductCollection($data);
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
    public function store(StoreProductPost $request)
    {
        //
        $data = $request->only(['product_name', 'product_number', 'product_img','product_amount','remain_amount','order_id','buy_date', 'remark']);
        $timeStamp = $data['buy_date'];
        $data['buy_date'] = Carbon::createFromTimestamp($timeStamp);
        if (Product::create($data)){
            return $this->success();
        } else {
            return $this->error();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        return new \App\Http\Resources\Product($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductPut $request, Product $product)
    {
        //
        $data = $request->only(['product_name', 'product_number', 'product_img','product_amount','remain_amount','order_id','buy_date', 'remark']);
        $timeStamp = $data['buy_date'];
        $data['buy_date'] = Carbon::createFromTimestamp($timeStamp);
        if (Product::where('id', $product->id)->update($data)) {
            return $this->success();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //删除产品的同时删除对应的包裹信息和包裹详细信息

        $productId = $product->id;
        $orderId = $product->order_id;
        DB::transaction(function () use($product, $productId, $orderId) {
            // 删除产品信息
            $product->delete();
            // 删除详细包裹信息并同时获取包裹信息,并调整包裹信息
            $arrPackage = Detail::where('product_id', $productId)->pluck('package_id');
            $arrPackage = $arrPackage->toArray();
            Detail::where('product_id', $productId)->delete();
            foreach ($arrPackage as $packageId){
                $count = Detail::where('package_id', $packageId)->count();
                // 删除指定的产品后 该包裹没有其它的数据了，则直接删除包裹信息，对应一个包裹只有装载一个产品的情况
                if ($count === 0) {
                    Package::where('id', $packageId)->delete();
                }
            }
            // 计算现在订单的状态
            $productAmount = Product::where('order_id', $orderId)->sum('product_amount');
            $remainAmount =  Product::where('order_id', $orderId)->sum('remain_amount');
            // 状态为出售完
            if ($remainAmount === 0) {
               Order::where('id', $orderId)->update([
                   'status' => 3
               ]);
            }
            // 状态为未出售
            if ($remainAmount === $productAmount) {
                Order::where('id', $orderId)->update([
                    'status' => 1
                ]);
            }
            // 状态为出售未完
            if ($remainAmount < $productAmount) {
                Order::where('id', $orderId)->update([
                    'status' => 2
                ]);
            }
        });
        return $this->success();
    }
}
