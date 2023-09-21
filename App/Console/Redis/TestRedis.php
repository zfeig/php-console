<?php

namespace App\Console\Redis;


use App\Console\Base;
use App\Func\RedisTool;

class TestRedis extends Base{

    public function run(){
        $cateData = RedisTool::getInstance()->get('test');
        print_r(
            [
                'code' => 200,
                'data' => $cateData
            ]);
    }
}