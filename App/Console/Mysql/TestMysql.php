<?php

namespace App\Console\MySql;


use App\Console\Base;
use App\Func\Config;
use Illuminate\Database\Capsule\Manager as DB;

class TestMysql extends Base{
    public function run(){

        echo "base path: ".BASE_PATH.PHP_EOL;

        $db = Config::get('db');
        var_dump($db);

        $group = DB::table('group')->where('id','>',1)->get();
        print_r($group);
       
    }

}