## 自动加载机制
```php
public static function autoload($class) {
        // 检查是否存在映射
        if(isset(self::$_map[$class])) {
            include self::$_map[$class];
        }elseif(false !== strpos($class,'\\')){//如果加载的class名字中没有 \
          $name           =   strstr($class, '\\', true);
          //before_needle为 TRUE，strstr() 将返回 needle 在 haystack 中的位置之前的部分。

          if(in_array($name,array('Think','Org','Behavior','Com','Vendor')) || is_dir(LIB_PATH.$name)){ 
              // Library目录下面的命名空间自动定位
              $path       =   LIB_PATH;
          }else{
              // 检测自定义命名空间 否则就以模块为命名空间
              $namespace  =   C('AUTOLOAD_NAMESPACE');
              $path       =   isset($namespace[$name])? dirname($namespace[$name]).'/' : APP_PATH;//./Application/
          }
          //替换左右分隔符
          $filename       =   $path . str_replace('\\', '/', $class) . EXT;
          if(is_file($filename)) {
              // Win环境下面严格区分大小写 realpath返回绝对路径
              if (IS_WIN && false === strpos(str_replace('/', '\\', realpath($filename)), $class . EXT)){
                  return ;
              }
              //包含文件
              include $filename;
          }
        }elseif (!C('APP_USE_NAMESPACE')) {
            // 自动加载的类库层
            foreach(explode(',',C('APP_AUTOLOAD_LAYER')) as $layer){
                if(substr($class,-strlen($layer))==$layer){
                    //存在'Controller,Model' 就走自身的一个引入缓存机制
                    if(require_cache(MODULE_PATH.$layer.'/'.$class.EXT)) {
                        return ;
                    }
                }            
            }
            // 根据自动加载路径设置进行尝试搜索 否则根据自定义的路径进行一个自动加载
            foreach (explode(',',C('APP_AUTOLOAD_PATH')) as $path){
                //加载第三方类库
                if(import($path.'.'.$class))
                    // 如果加载类成功则返回
                    return ;
            }
        }
    }
```
## 致命错误捕获
```
// 致命错误捕获
    static public function fatalError() {
        Log::save();//自动加载
        if ($e = error_get_last()) {
            switch($e['type']){
              case E_ERROR:
              case E_PARSE:
              case E_CORE_ERROR:
              case E_COMPILE_ERROR:
              case E_USER_ERROR:  
                ob_end_clean();
                self::halt($e);
                break;
            }
        }
    }
```