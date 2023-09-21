<?php 
namespace App\Controller;
use App\Response\Response;
use App\Lib\ExcelToolService;
use Context;
use App\Func\Config;
use App\Func\MongoTool;
use App\Func\RedisTool;
use Illuminate\Database\Capsule\Manager as DB;


//设置全局常量
define("ROOT_PATH",str_replace(str_replace("\\","/",__NAMESPACE__),"",__DIR__));
define("BASE_PATH",ROOT_PATH.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Controller".DIRECTORY_SEPARATOR);
define("STATIC_PATH",ROOT_PATH."statics");
define('APPLICATION_ENV', $_ENV['SERVER_ENV'] ?? 'test');




  class Base {

	

    public  $params;

	public  $basePath;


	public $whiteList = [
		'Symfony\Component\Translation\TranslatorInterface'
	];



	

    public function __construct() {
        
		$this->basePath = $this->getBasePath();		

        // echo "base path is:".$this->basePath.PHP_EOL;
		$this->bootstrap();

		$this->initContext();

    }

    public  function setParams($params){
        $this->params = $params;
    }


	public  function getParams(){
        return $this->params;
    }

	
	public function bootstrap() {
		$this->autoLoadClass();
		$this->loadConfig();
		$this->loadDB();
		$this->loadCache();
		$this->loadMongo();
	}



	/**
	 * 全局挂载某些常用的类的实例
	 */
	public function initContext() {
		Context::set(new Response());
		Context::set(new ExcelToolService());
	}



	public function getBasePath() {
	   
		 return ROOT_PATH;
	}



	public function autoLoadClass() {
		//自动加载第三方扩展类库
		require_once  ROOT_PATH.'vendor/autoload.php';

		//自动加载命名空间下扩展库
		spl_autoload_register(function($className)
		{
			$currentFile = $this->basePath. str_replace("\\","/",$className) .".php";
			// echo "try to auto load ".$currentFile.PHP_EOL;
			if (file_exists($currentFile)) {
				require_once "{$currentFile}";
			} else if(in_array($className,$this->whiteList)){
				if (PHP_SAPI == "cli") {
					fwrite(STDIN,"ignore error load class:".$className);
				}  
			} else{
				throw new \Exception("class {$className} is not exist!");
			}
		});
	}


	public function loadConfig(){

		 if (empty(Config::all())){

			$confPath = $this->basePath."config".DIRECTORY_SEPARATOR;
		
			$baseData = require_once $confPath."config.php";
	
			$envData = require_once $confPath.APPLICATION_ENV.".php";

			Config::set(array_merge($baseData,$envData));
		 } else{
			fwrite(STDIN,"***********config has loaded***********".PHP_EOL);
		 }	
	}


	public function loadDB(){

		$db = new DB;

		$dbConf = Config::get('db')['mysql'];
		$db->addConnection([
			'driver' => $dbConf['driver'],
			'host' => $dbConf['host'],
			'database' => $dbConf['database'],
			'username' => $dbConf['user'],
			'password' => $dbConf['pwd'],
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => $dbConf['prefix'],
		]);

		$db->setAsGlobal();
		$db->bootEloquent();
	}


	public function loadCache(){

	   if(empty(RedisTool::getInstance())) {
			$rdsConf = Config::get('redis');
			RedisTool::connect($rdsConf);
	   }else{
			
			fwrite(STDIN,"***********redis has conneced !***********".PHP_EOL);
	   }

	}


	public function loadMongo(){
		
       if(empty(MongoTool::getInstance())) {
			$mongoConf = Config::get('mongo');   
			MongoTool::connect($mongoConf);
	   }else{
			fwrite(STDIN,"***********mongo has conneced !***********".PHP_EOL);
	   }
	}
    
}