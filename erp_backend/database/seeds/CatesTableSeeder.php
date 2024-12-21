<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $typeLists = ['文具','发带', '鞋子', '家居', '母婴', '首饰', '服饰', '玩具', '箱包'];
        $data = [];
        foreach ($typeLists as $v) {
            $item = [
               'name' => $v,
                'pid' => 0,
                 'created_at' => now(),
            ];
            $data[] = $item;
        }

        if (\App\Models\Cate::insert($data)) {
            echo '类型导入成功';
            $lists = DB::table('cates')->get(['id', 'name']);
            Redis::set('category', $lists);
        } else {
            echo '类型导入失败';
        }
    }
}
