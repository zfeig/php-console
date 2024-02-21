<?php 

namespace App\Console\Advt;


use App\Func\Config;
use App\Lib\ExcelToolService;
use App\Console\Base;


class TestExcel extends Base{


    public function run() {

        $tool_service = new ExcelToolService();
        $data = $tool_service->readFile("itms.xlsx");
        print_r($data);

        // $cfgs = Config::all();
        // print_r($cfgs);

        $db = Config::get('db');
        var_dump($db);

    }
}