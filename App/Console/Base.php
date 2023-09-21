<?php 
namespace App\Console;

//设置全局常量
define("ROOT_PATH",str_replace(str_replace("\\","/",__NAMESPACE__),"",__DIR__));
define("BASE_PATH",ROOT_PATH.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Console".DIRECTORY_SEPARATOR);
define("STATIC_PATH",ROOT_PATH."statics");
define('APPLICATION_ENV', $_ENV['SERVER_ENV'] ?? 'test');

use App\Func\Config;
use App\Func\MongoTool;
use App\Func\RedisTool;
use Illuminate\Database\Capsule\Manager as DB;



abstract class Base {

    public  $params;

	public  $basePath;


	abstract public function run();

    public function __construct($argv) {
        
		$this->params = $this->getParams($argv);
		$this->basePath = $this->getBasePath();		
		$this->bootstrap();
    }


	
	public function bootstrap() {
		$this->autoLoadClass();
		$this->loadConfig();
		$this->loadDB();
		$this->loadCache();
		$this->loadMongo();
	}

    public function getParams($argv) {
		$params = [];
		array_shift($argv);
		foreach ($argv as $k => $v) {
			$tmpArr = explode("=", $v);
			$params[reset($tmpArr)] = end($tmpArr);
		}
		return $params;
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
			echo "try to auto load ".$currentFile.PHP_EOL;
			if (file_exists($currentFile)) {
				require_once "{$currentFile}";
			} else{
				throw new \Exception("class {$className} is not exist!");
			}
		});
	}


	public function loadConfig(){

		$confPath = $this->basePath."config".DIRECTORY_SEPARATOR;
		
		$baseData = require_once $confPath."config.php";

		$envData = require_once $confPath.APPLICATION_ENV.".php";

		Config::set(array_merge($baseData,$envData));

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

		$rdsConf = Config::get('redis');
		RedisTool::connect($rdsConf);

	}


	public function loadMongo(){
		
		$mongoConf = Config::get('mongo');   
		MongoTool::connect($mongoConf);

	}
  
    
}