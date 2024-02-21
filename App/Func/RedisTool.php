<?php

namespace App\Func;


use Predis\Client;

class RedisTool {

    public static $redisClient;

    public static function connect($rdsConf){

   
        self::$redisClient = new Client([
			'host'   => $rdsConf['host'],
			'port'   => $rdsConf['port'],
			'database' => $rdsConf['db'],
			'password'=> $rdsConf['auth']
		]);

    
        if (PHP_SAPI == "cli") {
            fwrite(STDIN, "redis client connected !\n\r");
		}
    }


    public static function getInstance() {
        return self::$redisClient;
    }


}