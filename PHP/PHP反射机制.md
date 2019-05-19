# PHP反射机制 Reflection
 通过ReflectionClass，我们可以得到Person类的以下信息：
- 常量 Contants
- 属性 Property Names
- 方法 Method Names静态
- 属性 Static Properties
- 命名空间 Namespace
- Person类是否为final或者abstract
### 例子
```
<?php
class Person{
    private $_name;

    public $age;

    public function __construct(){
        $this->sex = "male";
    }

    public function action(){
        echo "测试";
    }
}
echo '<pre>';
$class = new ReflectionClass('Person');
//获取person类的属性名
foreach($class->getProperties() as $property) {
    echo $property->getName()."\n";//   _name  age
}
//获取person类的方法名
print_r($class->getMethods());/*[
        '0'=>ReflectionMethod Object('name'=>__construct,'class'=>'Person'),
        '1'=>ReflectionMethod Object('name'=>action,'class'=>'Person')
    ]*/

$p1 = new Person();
$obj = new ReflectionObject($p1);

//获取对象和类的属性名
print_r($obj->getProperties());/*[
        '0'=>ReflectionProperty  Object('name'=>_name,'class'=>'Person'),
        '1'=>ReflectionProperty  Object('name'=>age,'class'=>'Person'),
        '2'=>ReflectionProperty  Object('name'=>sex,'class'=>'Person')
    ]*/
    
```
从例子中可知 我们可以通过反射 拿到其属性方法等  也就可以完成自动加载等 操作

**==期待更好的用法==**