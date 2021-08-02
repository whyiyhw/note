
```
version_compare
function version_compare($version1, $version2, $operator = null) { }
此函数有三个参数 两个必选 第三个可选
执行流程为 先将 _ + 改成 . 然后 explod('.',$v) 最后将两个值进行对比 

返回值
默认 在第一个版本低于第二个时，version_compare() 返回 -1；如果两者相等，返回 0；第二个版本更低时则返回 1。 
当使用了可选参数 operator 函数将返回 TRUE 或者 FALSE。 

在框架中的使用
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 ! your PHP version is '.PHP_VERSION);
```
```
microtime
function microtime ($get_as_float = null) {}
当前 Unix 时间戳以及微秒数

返回值
如果给出了 get_as_float 参数并且其值等价于 TRUE，
microtime() 将返回一个浮点数。

在框架中的使用
$GLOBALS['_beginTime'] = microtime(TRUE);
//microtime(TRUE) 的值为 1520698240.0491 形式
超全局变量$GLOBALS 来保存初始值
使用全局变量
```
```
get_defined_constants
tp3.23 加php 自带的 初始 定义了1651个常量 
```

```
检查变量是否存在，使用 isset()

检测常量是否存在, 使用 defined()

检测函数是否存在，使用 function_exists()
```
```
error_get_last()
//获取关于最后一个发生的错误的信息 为一个数组
if ($e = error_get_last()) 
{//获取关于最后一个发生的错误的信息  返回一个数组
    switch($e['type']){
      case E_ERROR:
      case E_PARSE:
      case E_CORE_ERROR:
      case E_COMPILE_ERROR:
      case E_USER_ERROR:  
        ob_end_clean();
        break;
    }
}

ob_end_clean()// — 清空（擦除）缓冲区并关闭输出缓冲 返回bool 失败时警告error
//此函数丢弃最顶层输出缓冲区的内容并关闭这个缓冲区。如果想要进一步
//处理缓冲区的内容，必须在ob_end_clean()之前调用ob_get_contents()，
//因为当调用ob_end_clean()时缓冲区内容将被丢弃。 
```
# 字母函数 L
```
# 类似于C L('key','value') 短暂保存
# 取用 L('key')
# 一般可见于 多语言支持
```