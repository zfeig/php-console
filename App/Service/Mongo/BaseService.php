<?php
namespace App\Service\Mongo;




use App\Func\MongoTool;
use App\Func\Config;

class BaseService {

   
    protected static $client;


   
    protected static $cfg;




    public function __construct(){
        self::$cfg = Config::get('mongo');
        self::$client = MongoTool::getInstance();
    }


    /**
     * 
     */
    public   function getObject($className) 
    {
       
        $db = self::$cfg['db'];
        
        $tb = (new $className())->table;

        if (PHP_SAPI == 'cli') {
            echo sprintf("db:%s   collection:%s  tb:%s\n\r",$db,$className,$tb);
        }
       
        
        $collection = self::$client->{$db}->{$tb};

        return  $collection;
    }


    public function fmt($data)
    {
        return json_decode(json_encode($data,JSON_UNESCAPED_UNICODE),true);
    }

   
}