<?php
define("PROJECT_PATH",str_replace(str_replace("\\","/",__NAMESPACE__),"",__DIR__));
define("CONSOLE_PATH",PROJECT_PATH.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Console".DIRECTORY_SEPARATOR);

/**
 * @usage php console.php r=Mysql/TestMysql
 */

class Application {

    public $route;

    public $argv;


    public $defaultns = "App\\Console\\";

    public function __construct($argv){
         $this->argv = $argv;
    }


    public function getParams() {
        $params = [];
        array_shift($this->argv);
        foreach ($this->argv as $k => $v) {
            $tmpArr = explode("=", $v);
            $params[reset($tmpArr)] = end($tmpArr);
        }
        return $params;
    }


    public function parseRoute() {

        $this->route = explode("/",$this->getParams()['r']??'');

      

        if (empty($this->route)) exit("router r is required ! r=xx");
        
        array_walk($this->route ,function(&$itm) {
            $itm = ucfirst($itm);
        });

    }


    public static function useImplode($separator,$arr) {
        $str = "";
        foreach($arr as $k => $v) {
           $str.= sprintf("%s%s",$v, $k==count($arr)-1 ? "" : $separator);
        }
        return $str;
    }

    //auto_laod class
    public function loadClass() {
       
        require_once CONSOLE_PATH."Base.php";
        require_once CONSOLE_PATH. self::useImplode(DIRECTORY_SEPARATOR,$this->route).".php";
    }



    public function getInstance(){
        $className = sprintf("%s%s",$this->defaultns,self::useImplode("\\",$this->route));
        return new $className($this->argv);
    }

    public function start() {
        $this->parseRoute();
        $this->loadClass();
        return  $this->getInstance()->run();
    }


}



$app = new Application($argv);
$app->start();