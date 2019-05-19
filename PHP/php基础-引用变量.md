引用变量
什么是引用变量?如何去定义引用变量?

用不同的名称访问同一个变量,我们称之为引用变量
一般使用&去定义引用变量

通常我们知道引用传值,变量传值. 那么在php中我们如何去更深一步的理解
引用传值

扩展 引用变量的原理

首先我们需要看以下代码
```
<?php
# 内存c
$a = [1,2,3,4,5,6,7,8,9,10];
# 内存d
$b = $a;
# 内存e
$b = [1,2,3,5,8,6,3];
# 内存f
```
- 按照我们一般的理解 
    + 内存d应该是比内存c多

    + 内存e 应该是等于 e = d - c

    + 内存f应该比内存e 小

但实际情况是这样的 我使用php版本为5.6
```
<?php
echo memory_get_usage().PHP_EOL;//349632
$a = [1,2,3,4,5,6,7,8,9,10];
echo memory_get_usage().PHP_EOL;//350160
$b = $a;
echo memory_get_usage().PHP_EOL;//350160
$b = [1,2,3,5,8,6,3];
echo memory_get_usage().PHP_EOL;//350432

```
- `memory_get_usage()`

    + 返回当前分配给你的 PHP 脚本的内存量，单位是字节（byte）。
    
你会发现内存d,e居然是一样的

这个现象有个简称 叫==cow== **写时赋值** copy on write

俗称 ***只有在赋值的时候才会分配内存给变量***

至于为什么存在写时赋值  原因在于节省内存

写时赋值是怎么实现的呢?

我假设你和我一样都了解一点点的底层 一点点数据结构

(当然,有可能你知道的比我要多的多0.0)

zval中保存php变量使用了这样的结构体 

==**(以下都是php5的zval架构)**==
```
//变量结构体 (即为存储变量的地方)
struct _zval_struct {
    zvalue_value value;         /*变量的值*/
    zend_uint refcount__gc;     /*指向该结构体的变量数*/
    zend_uchar type;            /*变量类型*/
    zend_uchar is_ref__gc;      /*是否为引用变量 1:是 0:否*/
}

//那么这样分析在 $b=$a的时候
--$b --$a 
struct _zval_struct {
    zvalue_value value:[1,2...];    /*变量的值*/
    zend_uint refcount__gc:2;       /*指向该结构体的变量数*/
    zend_uchar type:IS_ARRAY;       /*变量类型*/
    zend_uchar is_ref__gc:0;        /*是否为引用变量 1:是 0:否*/
}
//都是使用的同一个结构体
//而在给$b赋值[1,2,3...]的时候$b才拥有自己的结构体
//写时赋值可不止这一种方式,但这是最容易理解的方式
```
回到正题 引用的原理
```
<?php
//开启xdebug扩展
$a = [1,2,3];
xdebug_debug_zval("a");
```
```
# 这是xdebug打印的值
a: (refcount=1, is_ref=0) = 
array ( 
        0 => (refcount=0, is_ref=0)=1,
        1 => (refcount=0, is_ref=0)=2,
        2 => (refcount=0, is_ref=0)=3,
    )
```
我们使用xdebug来检测我们的之前的推论

观察上述代码会发现正如我们所预料的 

refcount 为 1
is_ref 为 0

继续
```
$a = [1,2,3];
$b = $a;
xdebug_debug_zval("a");
a: (refcount=2, is_ref=0) = 
array ( 0 => (refcount=0, is_ref=0)=1,
        1 => (refcount=0, is_ref=0)=2,
        2 => (refcount=0, is_ref=0)=3
    )
```
一气呵成
```
$a = [1,2,3];
$b = &$a;
xdebug_debug_zval("a");
a: (refcount=2, is_ref=1) = 
array ( 0 => (refcount=0, is_ref=0)=1,
        1 => (refcount=0, is_ref=0)=2,
        2 => (refcount=0, is_ref=0)=3
    )
$a = [3,2,1];
xdebug_debug_zval("a");
a: (refcount=2, is_ref=1) = 
array ( 0 => (refcount=0, is_ref=0)=3,
        1 => (refcount=0, is_ref=0)=2,
        2 => (refcount=0, is_ref=0)=1
    )

```
根据上述对于结构体的分析  我们不难得出结论

引用传址 实际上是改变了变量结构体中 

指向结构体的变量数  
是否引用的值

可能会不好理解的点
- ==**unset不会销毁被引用后值的内存** 只是改变了引用的值==
```
$a = 1;
$b = &$a;
xdebug_debug_zval("a");//a: (refcount=2, is_ref=1)=1
unset($b);
xdebug_debug_zval("a");//a: (refcount=1, is_ref=1)=1
echo $a; //1
```
观察下面代码
```
<?php
echo memory_get_usage().PHP_EOL;//350184
$a = [1,2,3,35,5];
echo memory_get_usage().PHP_EOL;//350488
unset($a);
xdebug_debug_zval("a");
# a: (refcount=0, is_ref=0)=*uninitialized*
echo memory_get_usage().PHP_EOL;//350216
$b = [1,2,3,35,5,5,6,5];
echo memory_get_usage().PHP_EOL;//350488
```
会发现最占内存的那部分正常情况是被删除了,
但是如果被引用了,这部分内存是不会被立即清除,
很诡异吧,猜一下以下的代码会输出什么
```
<?php
$foo ['test'] = 1;
$bar  = &$foo['test'];
$other = $foo;
$other['test'] = '2';
echo $foo['test']; 
```
答案是 2

分析一波
```
<?php
$foo ['love'] = 1;
xdebug_debug_zval("foo");
$bar  = &$foo['love'];
xdebug_debug_zval("foo");
$other = $foo;
xdebug_debug_zval("foo");
$other['love'] = '2';
xdebug_debug_zval("foo");
echo $foo['love']; 
```
```
# 最开始
foo: (refcount=1, is_ref=0) =
array ('love' => (refcount=0, is_ref=0)=1)

# 数组中的元素被引用了
foo: (refcount=1, is_ref=0) = 
array ('love' => (refcount=2, is_ref=1)=1)

# 写时重写 只增加了 引用数
foo: (refcount=2, is_ref=0) = 
array ('love' => (refcount=2, is_ref=1)=1)

# 触发写时重写 引用数减一 但是 数组中元素是被引用的 更改为2
foo: (refcount=1, is_ref=0) = 
array ('love' => (refcount=3, is_ref=1)='2')
2

```
看上去 两个不同的变量却会相互影响,这也是为什么&尽量少用的原因

除非你能真正理解以上的奇怪的"bug"?

就算理解了也不想赋值都要分析半天...

- **==对象都是引用传值==** 
- 这里是使用的php7所以会和5不一样但结论是一致的
```
<?php
class Foo
{
	public $name = "zhangsan";
}

$obj1 = new Foo();
xdebug_debug_zval("obj1");

$obj2 = $obj1;
xdebug_debug_zval("obj1");

$obj2->name = "lisi";
xdebug_debug_zval("obj1");
```
```
obj1: (refcount=1, is_ref=0) =
class Foo { 
public $name = (refcount=2, is_ref=0)='zhangsan' 
}

obj1: (refcount=2, is_ref=0) = 
class Foo { 
public $name = (refcount=2, is_ref=0)='zhangsan'
}

obj1: (refcount=2, is_ref=0) = 
class Foo {
public $name = (refcount=0, is_ref=0)='lisi' 
}
```
练习
```
$data = ['a','b','c'];
foreach ($data as $key => $value) {
	$value = &$data[$key];
	print_r($data);
}
```
每次打印的值
```
Array
(
    [0] => a
    [1] => b
    [2] => c
)
Array
(
    [0] => b
    [1] => b
    [2] => c
)
Array
(
    [0] => b
    [1] => c
    [2] => c
)
```

php7中zval主要的变化就是
1. zval不再单独分配内存，
2. 不自己存储引用计数。
3. 整型浮点型等简单类型直接存储在 zval 中。
4. 复杂类型则通过指针指向一个独立的结构体。

这样就能解释
如果你将我上面例子中的数组改成数值型 会发现内存的变化和想象中不一致的问题(会偏小)


https://segmentfault.com/a/1190000004124429
