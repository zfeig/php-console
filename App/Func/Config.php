<?php 

namespace App\Func;

class Config {
    public static $cfg ; 
    public static function set($data) {
        self::$cfg = $data;
    }

    public static function get($k = '',$default='') {

        return  isset(self::$cfg[strtolower($k)]) ? self::$cfg[strtolower($k)] : $default;
    }


    public static function all() {
        return self::$cfg;
    }

}

