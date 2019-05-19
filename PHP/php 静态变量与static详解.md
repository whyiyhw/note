### static

### static 变量/属性
1.static 放在函数内部修饰变量
```
<?php
function test() {
    static $value = 1;
    echo $value;
    $value++;
}

test();  // 1
test();  // 2
test();  // 3

echo $value;//PHP Notice:  Undefined variable

```
上述结论就是静态直接存函数作用域的内存中 第一次声明并赋值有效

多次声明无效

2.static放在类里修饰属性，或方法
```
<?php
class Test {
    static $id = 0;
 
    function __construct() {
        self::$id++;
    }
 
    static function getId() {
        return self::$id;
    }
}
echo Test::$id;   //0
 
$p1=new Test();
$p2=new Test();

echo Test::$id;   //2

class A extends Test
{
	public function getId1()
	{
		echo parent::$id;
	}

	public function getId2()
	{
		echo Test::$id;
	}
}

($ok = new A)->getId1();//3
($ok = new A)->getId2();//4

```
在类内部的 静态变量 可以理解为属于类的一个存在于内存中的属性

+ 所以内部调用为 self::
+ 外部调用为 className::
+ 子类继承调用为 parent::


3.static放在类的方法里修饰变量

4.static修饰在全局作用域的变量

