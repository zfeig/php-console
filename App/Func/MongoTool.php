<?php

namespace App\Func;


use \MongoDB\Client AS MongoClient;

class MongoTool {

    public static $mongoClient;


    public static function connect($mongoConf){

        self::$mongoClient =    new MongoClient(
			sprintf('mongodb://%s:%d', $mongoConf['host'], $mongoConf['port']),
			[
				'authSource' => 'admin',
				'username' => $mongoConf['user'],
				'password' =>  $mongoConf['pwd'],
			]
		);

		if (PHP_SAPI == "cli") {
			fwrite(STDIN, "mongo client connected !\n\r");
		}
		
    }



    public static function getInstance() {
        return self::$mongoClient;
    }
}