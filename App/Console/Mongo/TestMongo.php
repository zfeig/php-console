<?php
namespace App\Console\Mongo;


use App\Console\Base;
use App\Service\Mongo\ProductService;


class TestMongo extends Base{

    public function run(){

        $product_service = new ProductService();
        $data = $product_service->getConf();

        var_dump($data);

    }
   
}