<?php
namespace App\Service\Mongo;
use App\Service\Mongo\BaseService;
use App\Task\ProductTask;
use \MongoDB\BSON\ObjectId;

class ProductService extends BaseService{

    public   function getConf() 
    {

        $data = $this->getObject(ProductTask::class)->find(
            [
                'product_id' => 
                    [
                        '$in' => 
                            [
                                10003,10002
                            ]
                    ]
            ],
            [
                'limit' => 10,
                'projection' => 
                    [
                        '_id' => 0
                    ]
                ]
            );
     
        //mongo find时需要使用该函数，否则返回数据为空    
        $bkData = iterator_to_array($data,false);



        $dataOne = $this->getObject(ProductTask::class)->findOne(['product_id' => 10006]);


        $targetData = $this->getObject(ProductTask::class)->findOne(
                [
                    '_id' => new ObjectId('63edd4fa1827b2fa4f3f89a6')
                ]
        );




        return [
             'conf' => self::$cfg,
             'product' =>  $this->fmt($bkData),
             'single' => $this->fmt($dataOne),
             'target' => $this->fmt($targetData),
        ];

    }

}