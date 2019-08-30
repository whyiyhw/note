# 匿名函数/php闭包

## 在php中，闭包都是Closure类的实例。
```
$eg = function($name = 222){ echo '111' . $name; };

$eg();

var_dump($eg instanceof Closure);  //bool(true)
```
+ closure类有三个方法
    - 1 **__construct(void)** 实例化方法  用以禁止实例化
    - 2 **public static Closure bind ( Closure $closure , object $newthis [, mixed $newscope = 'static' ] )**
    - 3 **public Closure bindTo ( object $newthis [, mixed $newscope = 'static' ] )**
    
+ 匿名函数（Anonymous functions），也叫闭包函数（closures），允许 临时创建一个没有指定名称的函数。
```php
$eg = function($name = 222){ echo '111' . $name; };
$eg();//最常见第一种用法 声明 匿名函数 赋值给变量
```

+ 最经常用作回调函数（callback）参数的值。当然，也有其它应用的情况。

