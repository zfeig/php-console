<?php
define("PROJECT_PATH",str_replace(str_replace("\\","/",__NAMESPACE__),"",__DIR__));
define("CONTROL_PATH",PROJECT_PATH.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Controller".DIRECTORY_SEPARATOR);

/**
 * @usage http://127.0.0.1:9001/?r=User/Index/Index&uid=1
 */

use \Swoole\Http\Server AS WebServer;

class Server {

    public $route;

    public $params;

    public $defaultpt = 9001;

    public $defaultrt = "Index/Index/Index";
    
    public $defaultns = "App\\Controller\\";




    public function parseRoute() {


        if(empty($this->params['r']))  $this->params['r'] = $this->defaultrt;

        $tmpArr = explode("/",$this->params['r']??'');

        if (count($tmpArr) !=3) {
            throw new \Exception("invalid route params!");
        }
    
        
        array_walk($tmpArr ,function(&$itm) {
            $itm = ucfirst($itm);
        });

        $this->route = [
            'module' => $tmpArr[0],
            'controller' => $tmpArr[1],
            'method' => $tmpArr[2]
        ];

        //去掉特殊路由参数
        unset($this->params['r']);
        
    }


    //auto_laod class
    public function dispath() {
       
        $basePath =  CONTROL_PATH."Base.php";

        $scriptPath =  CONTROL_PATH. sprintf("%s%s%sController",$this->route['module'],DIRECTORY_SEPARATOR,$this->route['controller']).".php";

        foreach([$basePath,$scriptPath] as $r) {
            if (!file_exists($r)) {
                throw new \Exception("file {$r} is not exist!");
            }
            
            require_once $r;
        }
    }



    public function getInstance(){

        $className = sprintf("%s%s\\%sController",$this->defaultns,$this->route['module'],$this->route['controller']);

        $actionName = sprintf("%sAction",$this->route['method']);

        if (!class_exists($className)) {
            throw new \Exception("class name:".$className." is not exist!!");
        }


        if (!method_exists($className,$actionName)) {
            throw new \Exception("method name:".$actionName." is not exist!");
        }



        if(!Context::has($className)){
            $instance = new $className();
            Context::set($instance);
        } else{
            $instance = Context::get($className);
        }

        $instance->setParams(array_merge($this->params,$this->route));


        $res =  $instance->{$actionName}();

        //debug
        // var_dump([
        //     'class_name' => $className,
        //     'action_name' => $actionName,
        //     'route' => $this->route,
        //     'params'=>$instance->getParams(),
        //     'res' => $res
        // ]);

         return $res;

    }

    

    public function start() {

        $http = new WebServer("0.0.0.0", $this->defaultpt);


        $http->on('request', function ($request, $response)  {

            try{

                $this->params = array_merge($request->get??[],$request->post??[]);

                $this->parseRoute();
                
                $this->dispath();
                
                $resData = $this->getInstance();
                
                $response->header("Content-Type", "application/json; charset=utf-8");

            }catch(\Throwable $e) {

                $response->header("Content-Type", "text/html; charset=utf-8");

                $resData = $e->getMessage();
            }
            
            $response->end($resData);

        });
        

        $http->start();
    }
}


class Context{

    public static $map;


    public static function set($instance) {
        
        $className = get_class($instance);
        
        if(!self::has($className)){
            self::$map[$className] = $instance;
        }
    }


    public static function has($className) {
        return !empty(self::$map)  && array_key_exists($className,self::$map);
    }


    public static function get($className) {
        if (self::has($className)) {
            return self::$map[$className];
        }
        return null;
    }

}



$sv = new Server();
$sv->start();