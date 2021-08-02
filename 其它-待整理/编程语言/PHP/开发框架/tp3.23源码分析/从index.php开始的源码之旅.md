## 1. 用户URL请求调用应用入口文件（通常是网站的index.php）

```
# index.php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

```
## 2. 载入框架入口文件（ThinkPHP.php）
```
// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
//定义各种常量
// URL 模式定义
const URL_COMMON        =   0;  //普通模式
const URL_PATHINFO      =   1;  //PATHINFO模式
const URL_REWRITE       =   2;  //REWRITE模式
const URL_COMPAT        =   3;  // 兼容模式

// 类文件后缀
const EXT               =   '.class.php';
```
## 3. 载入框架核心的运行
```
// 加载核心Think类
require CORE_PATH.'Think'.EXT;//全路径/ThinkPHP/Library/Think/Think.class.php

// 应用初始化 
Think\Think::start();

// 类映射存储
private static $_map      = array();

// 实例化对象存储
private static $_instance = array();


// 注册AUTOLOAD方法
spl_autoload_register('Think\Think::autoload');//加载一个不存在的类名时调用      
// 设定错误和异常处理
//注册一个 callback ，它会在脚本执行完成或者 exit() 后被调用。
register_shutdown_function('Think\Think::fatalError');

set_error_handler('Think\Think::appError');
set_exception_handler('Think\Think::appException');
```
