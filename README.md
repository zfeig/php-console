# php-console



## 1、项目简介

十多个核心文件，百行代码，实现极简php框架，拥有完整功能，重点学习和掌握php框架的基本实现原理和部署，本框架特点如下：

* 支持PSR代码规范
* 支持MySQL,MongoDB等常用数据库
* 支持Redis缓存操作
* 支持web服务器(基于swoole和原生模式)，实现简单MVC
* 支持cli运行时下console脚本模式
* 支持第三方扩展
* 支持docker环境和本地环境简单部署





## 2、项目结构

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
|----Func
|----Lib
|----Logic
|----|----Advt
...
|----Model
!----AbstractModel.php
|----|----User
...
|----Response
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





