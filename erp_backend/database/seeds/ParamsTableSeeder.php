<?php

use Illuminate\Database\Seeder;

class ParamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data=[
            'avg_sell' => [
                'desc' => '计算销量的天数',
                'value' => 30,
                'remark' => '销量统计的天数及计算日销量的天数'
            ],
            'good_day' => [
                'desc' => '设置到货的天数',
                'value' => 4,
                'remark' => '设置警戒数量时用到的到货天数'
            ],
            'stork_day' => [
                'desc' => '库存天数',
                'value' => 7,
                'remark' => '购买商品时的库存天数'
            ],
            'min_count' => [
              'desc' => '最低采购量',
              'value' => 5,
              'remark' => '如果计算出来的采购量小于该参数，就使用本参数'
            ],
            'min_stock' => [
                'desc' => '最低库存',
                'value' => 2,
                'remark' => '库存为零，且销量非常低的情况下，使用该参数作为采购量'
            ],
            'ignore_count' => [
                'desc' => '单次购买过多的数量',
                'value' => 100,
                'remark' => '单次购买过多，造成统计出现不可预料的一些偏差, 忽略该次购买'
            ]
        ];

        foreach ($data as $key=>$value){
            \App\Models\Param::create([
                'name' => $key,
                'desc' => $value['desc'],
                'value' => $value['value'],
                'remark' => $value['remark']
            ]);
        }

    }
}
