# 手写实现php极简框架项目



## 1、项目简介

十多个核心文件，百行代码，就可以手写实现极简```php```轻量级框架，并拥有常用功能。项目初衷是秉承大道至简理念，能够在本地机器环境下，快速搭建项目脚手架，实现常用脚本功能实现和```API``` ```demo```等，不需要太复杂和花哨的功能和复杂的重型框架学习与安装依赖，主打一个极简和快速上手。

通过以下文档，可以快速理解和掌握项目的基本实现原理和部署，项目抛砖引入，项目特点如下：

* 支持```PSR```代码规范
* 支持```MySQL```,```MongoDB```等常用数据库
* 支持```Redis```缓存操作
* 支持```web``` 服务器(基于```Swoole```和原生模式)，实现简单```MVC```
* 支持```cli```运行时下```console```脚本模式
* 支持第三方扩展
* 支持```docker```环境和本地环境简单部署



## 2、项目结构

### 2.1 文件结构图

```shell
App
|----Console
!----|----Base.php
|----|----Advt
|----|----Mysql
|----|----Mongo
|----|----Redis
...
|----Controller
!----|----Base.php
|----|----Index
|----|----User
...
|----Response
|----Constant
|----Func
|----Lib
|----Logic
|----|----Advt
...
|----Model
!----AbstractModel.php
|----|----User
...
|----Service
|----|----Mongo
|----|----|----BaseService.php
...
|----|----User
...
|----Task
|----|----BaseTask.php
...
config
statics
composer.json
console.php
index.php
```



### 2.2 结构详解

项目遵循主流框架常用代码结构，结构清晰明了，易于扩展，包括入口文件，配置文件，静态文件、项目依赖文件、```App```文件等核心文件，下面简要介绍核心文件功能：



#### 2.2.1 根目录文件：

项目根目录文件负责项目入口功能，包含了基础配置，依赖管理，程序入口等功能分区等，功能固定，不需要扩展

* ```index.php```  项目 入口文件之一,内置基于```Swoole```扩展的```http```服务器,负责参数解析、```mvc``` 路由解析、应用实例创建和请求响应输出等核心功能

* ```console.php``` 项目入口文件之一,原生```cli```模式实现命令行脚本，支持固定路由模式和基本参数解析，简单实现业务脚本处理

* ```composer.json``` 项目依赖注册文件，基于```composer```方便快速安装扩展第三方包应用

* ```statics``` 静态目录，方便自动任务或者```web```控制器处理文件的工具目录

* ```config``` 配置文件，支持默认配置+环境变量配置模式，数组化节点配置，简单好理解



#### 2.2.2  应用目录文件：

应用目录文件也就是```App```文件夹下的各个子文件组合，承接着框架控制器应用和自动任务应用，以及各种公共类库服务，全局功能定义，支持按业务区分扩展等，以下安装功能模块进行讲解：



##### 2.2.2.1 Controller：

**```Controller``` ** 控制器目录，该目录构成为：```Base.php``` 加上多个 模块控制器的的组合，其中```Base.php ``` 为控制器父类的实现，具体业务控制器都要继承该类，如```Controller/Index/IndexController.php```  继承自父类控制器，*Index*是默认的控制器模块，```IndexController```是默认控制器；模块命名规范为首字母大写，模块控制器命名规范为驼峰法控制器名+```Controller.php``` ,控制器类名必须和文件名保持一致。

如果想扩展一个*Student*模块控制器，可以新建```Controller/Student/IndexController.php``` 文件，具体写法可参考Index模块控制器即可，代码示例如下：

```php
<?php
namespace App\Controller\Student;
use Context;
use App\Controller\Base;
use App\Response\Response;

class IndexController extends Base{
 
    public function IndexAction(){
        $sid = $this->params['id']?:1;
        return Context::get(Response::class)->success(
          'hello,student', 
          [
              'sid' => $sid
          ]
        );
    }
}
```



##### 2.2.2.2 Console：

**```Console``` ** 自动任务目录，该目录构成和**Controller**类似：```Base.php``` 加上多个 模块的组合，其中```Base.php ``` 为自动任务父类的实现，具体业务自动任务都要继承该类，如```Console/Index/IndexConsole.php```  *Index*是默认的自动任务模块，```IndexConsole.php```是模块默认自动任务文件，模块命名规范上模块名首字母大写，自动任务命名比较灵活，首字母大写和驼峰发组合，类命和文件名保持一致；如```Console/Mysql/TestMysql.php``` 表示```Mysql```模块下的```TestMysql```自动任务，示例代码如下：

```php
<?php

namespace App\Console\MySql;

use App\Console\Base;
use App\Func\Config;
use Illuminate\Database\Capsule\Manager as DB;

class TestMysql extends Base{
    public function run(){

        $db = Config::get('db');
        var_dump($db);

        $group = DB::table('group')->where('id','>',1)->get();
        var_dump($group);
    }
}
```



##### 2.2.2.3 公共模块：

公共模块是框架中的核心类库文件，如常量定义、核心函数库、响应类、助手类、模型、服务等，这些文件均可以被Controller和Console复用，因此被视为基础公共文件部分，下面分别介绍各个文件模块主要功能。

```Constant``` 目录 系统常量定义

```Func``` 目录：常用工具类文件，使用场景，可在应用（Controller/Console）启动注入初始化，在```Model/Service/Logic```当中使用，可根据业务需求进行扩展

```Lib``` 目录：常用类库文件，使用场景，可在应用（Controller/Console）启动注入初始化，在```Model/Service/Logic```当中使用可根据业务需求扩展

```Logic``` 目录：业务逻辑封装，可在应用（Controller/Console）中直接调用，根据实际需求按功能模块目录区分

```Model``` 目录：DB封装，这里使用了```Laravel```框架的**```Eloquent ORM ``` **组件,按照```Laravel```方式进行数据模型封装即可

```Task```目录: 简单的```MongoDB```集合定义，这里定义了```Mongo collection```信息

```Response``` 目录：自定义了响应数据封装，使用场景，在应用启动注册，在控制器输出中使用

```Service``` 目录： 服务封装，注意这里将```MongoDB```相关操作进行了单独封装，其他模块服务也可以放在该目录下，常用使用场景，对一些主要数据库操作进行封装，方便其他程序模块调用	





## 3、代码解析

这里对一些核心文件进行解析，方便了解整个开发逻辑，结合具体示例，介绍一个完整控制器或自动任务调用链条，以加强对框架的理解，具体会以两条线：控制器和自动任务进行代码分析。




###  3.1、自动任务执行流程

以console.php文件为例说明：

```php
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
```



执行一个```Mysql```模块下的```TestMysql.php```脚本,命令如下：

```shell
php console.php r=Mysql/TestMysql
```



整个自动任务执行流程很简单，```Application```类接收```$argv```变量后进行实例化对象，实例对象执行start()方法，完成自动任务脚本执行，再看整个类都是基于start()方法定义函数功能。

```cli```下执行```php```时，使用```$argv```可以获取shell脚本全部参数列表，通过类的构造函数注入```$argv```变量赋值到类的属性```argv```上，再通过```getParams()```方法格式化参数，获取到有用的参数数组，方便后续路由数据解析。

```parseRoute()```方法实现具体路由解析，通过前面获取到传递的参数数组，可以拆分出自动任务信息，即通过```r```参数结果得到自动任务模块和脚本名,并格式化赋值到类的```route```属性上，方便后续使用。

完成路由变量解析后，开始执行```loadClass()```方法,核心逻辑就是引入自动任务目录下的父类```App/Console/Base.php```和继承父类的模块脚本，也就是解析路由结果中的文件信息，通过```loadClass()```完成了相关依赖文件的加载

```getInstance()->run()```方法，对```route```信息中的自动任务脚本类进行实例化并执行该类继续父类的抽象方法，得到最后的输出结果，整个自动任务执行完毕



在整个流程中，```App/Console/Base.php```中作为自动任务类的父类，地位相当重要，让我看下这个类代码实现：

```php
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
			//echo "try to auto load ".$currentFile.PHP_EOL;
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
```



这个抽象类是所有自动任务类的父类并定义了```run()```抽象方法，方便子类通过```run()```方法实现各自业务逻辑。本类核心功能在构造器中实现，除了参数和路径解析外，最重要的方法就是```bootstrap()```,这个函数做了以下初始化工作：

* autoLoadClass()  
* loadConfig()
* loadDB()
* loadCache()
* loadMongo()



```autoLoadClass```方法，顾名思义就是加载类文件，这个方法首先执行解决类加载问题，这里要区分根目录下```console.php```中的类加载，这里主要是对类的依赖做了加载，包括加载第三方类库和核心类库文件，保障本类引用的命名空间类类文件均可正常加载。

```loadConfig```方法，顾名思义就是加载配置文件，初始化配置信息，为程序下一步做准备

```loadDB```方法，顾名思义就是加载数据库实例，这里加载了流行的```laravel DB``` 组件

```loadCache```方法，顾名思义就是加载缓存实例，这里加载了```php-redis```缓存类库

loadMongo方法，顾名思义就是加载```MongoDB```实例,这里前面已有介绍



至此整个自动任务实例类完成了以上流程初始化后，就可以回到```console.php```流程中```getInstance```方法，进行实例对象进行业务处理了




### 3. 2、控制器执行流程



以```index.php```文件为例说明：

```php
<?php
define("PROJECT_PATH",str_replace(str_replace("\\","/",__NAMESPACE__),"",__DIR__));
define("CONTROL_PATH",PROJECT_PATH.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Controller".DIRECTORY_SEPARATOR);

/**
 * @stat php index.php 
 * @http http://127.0.0.1:9001/?r=Index/Index/Index&uid=1
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
```



```controller```整个流程和```console```类似，不同之处在于，```controller```引入了基于```Swoole```的```http```服务器，采用常驻的 ```cli``` 运行模式，每次请求不用加载全部项目代码，效率更高。因此新增了**```Context```**对象方便请求实例的复用和核心类的挂载。



启动```web```服务器

```shell
php index.php
```



访问路由控制器

```shell
curl -XGET http://127.0.0.1:9001/?r=user/index/index&uid=1
```



## 4、项目部署

介绍常用部署方法，需要根据业务场景合理选择，包括本地部署和docker容器部署二种方式，下面分别介绍



### 4.1 本地部署

本地部署需要在本地安装```php```版本推荐7.1以上，同时需要安装```swoole```扩展，```mongodb```扩展和```redis```扩展等，安装完成后，进入项目根目录，安装依赖即可执行。



#### 4.1.1 php安装：

```php```安装比较基础，这里省略



#### 4.1.2 ```Mongo```扩展安装：

进入http://pecl.php.net/package/mongodb 选择合适的适配版本,完整安装命令如下

```shell
wget http://pecl.php.net/get/mongodb-1.6.0.tgz
cd /mongodb-1.6.0
phpize
./configure
 make && make install
```

安装完毕后，根据安装结果提示，将```mongodb.so```扩展文件引入到```php```配置文件下即可



#### 4.1.3 ```Swoole```扩展安装：

进入http://pecl.php.net/package/swoole选择合适的适配版本,完整安装命令如下

```shell
wget http://pecl.php.net/get/swoole-4.5.0.tgz
cd /swoole-4.5.0
phpize
./configure
 make && make install
```

安装完毕后，根据安装结果提示，将```swoole.so```扩展文件引入到```php```配置文件下即可



#### 4.1.4 ```Redis```扩展安装：

进入http://pecl.php.net/package/swoole选择合适的适配版本,完整安装命令如下



```shell
wget http://pecl.php.net/get/redis-2.0.0.tgz
cd /redis-2.0.0
phpize
./configure
 make && make install
```

安装完毕后，根据安装结果提示，将```swoole.so```扩展文件引入到```php```配置文件下即可



#### 4.1.5 ```Composer```依赖安装：

```shell
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```



安装完成composer后，进入项目根目录安装依赖：

```shell
sudo composer install
```



#### 4.1.6 开启项目

如果只是用来跑自动任务的场景，执行如下：

```
php console.php r=mysql/testMysql uid=1
```



如果是用来跑```API```接口或者```web```页面，需要开启```http```服务,再访问页面路由：

```
php index.php #开启http服务
```



也可同时执行自动任务和进行web服务，根据自己实际项目选择即可





### 4.2 docker打包部署

项目支持在```docker```容器环境下打包运行，本机需要提前安装好```docker```环境，在完成打包镜像和开启容器后，会自动开启```web```服务，容器启动成功后可直接在宿主机下进行页面访问



#### 4.2.1 构建镜像：

构建镜像时注意项目所在目录路径，示例项目目录为```/disks/F/php-console-v2```,进入项目目录

```shell
sudo docker build -t php-console:v2 .
```



#### 4.2.2 开启容器：

```
sudo docker run --name php-console-v2 -p 9001:9001  -v /disks/F/php-console-v2:/opt/www:rw  --restart=always -d  php-console:v2
```



#### 4.2.3 自动任务执行：

进入容器内部，执行脚本入口文件

```shell
sudo docker exec -it php-console-v2 /bin/bash
php console.php r=mysql/testMysql uid=1
```



#### 4.2.4 查看容器日志：

```
sudo docker logs -f --tail 100 php-console-v2
```



#### 4.2.5 更新代码：

宿主机更新代码后，需要手动重启容器

```
sudo docker restart php-console-v2
```







## 5、关于其他

以上简单介绍了整个项目细节，由于时间仓促，项目本身还存在一定不完善的地方，可根据自己实际情况进行优化和重写，比如视图这块写的很简单，没有引入模板引擎等，比如没有专门日志接口等，读者可自行实现完善。



### 5.1 代码下载

提供项目下载地址，飞书下载地址



### 5.2 命名规范

模块名称保持首字母大写

方法名首字母小写，并遵守驼峰法命名

控制器遵守驼峰法，首字母大写，类名和文件名保持一致

自动任务遵守驼峰法，首字母大写，类名和文件名保持一致

类库文件遵守驼峰法，首字母大写，类名和文件名保持一致



### 5.3 注意事项

 未完待续

