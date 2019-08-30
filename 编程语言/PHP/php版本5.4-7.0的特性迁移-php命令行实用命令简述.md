## php5.4
trait 用于模型中的共用部分  比如说 提示/权限

可使用短数组[]

function()[0]函数调用的结果支持直接索引

(new obj)->foo;也支持这种形式的调用

## php5.5
mysql 被弃用 强行使用会报错--已废弃


yield 生成器 只加索引
```
function xrange($start,$end,$step = 1){
	for ($start; $start<=$end ; $start+=$step) { 
		yield $start;
	}
}
echo "<hr />";
foreach (xrange(1,10,2) as $key => $value) {
	echo $key."-->".$value."<br />";
}
```
yield 可使用于 大文件的读取

try{ }catch(){ }后的 finally{}关键字

finally里面的数据一定会被执行
```
$dsn = 'mysql:host=localhost;dbname=test;charset=utf8';
try{
	$pdo = new PDO($dsn,'root','');
}catch (PDOException $e){
	echo $e->getMessage();
	exit;
}
```
foreash 支持 list();
```
foreach ([[1,2],[2,3]] as list($a,$b)) {
	echo $a.'------->'.$b."<br />";
}
```
但是只支持索引数组 关联数组 不支持 感觉有点鸡肋
array_map()会有用一点

empty()支持表达式
isset不支持

## php命令行实用命令简述
php -m 显示已经加载的配置文件

php -S localhost:8080
开启内置的本地的端口为8080的web服务器
也可以是

php -S 0.0.0.0:8080/php -S 127.0.0.1:8080


php -f(-f 可省略) 路径/文件名
执行php脚本 php脚本不一定非要取名为.php后缀

php -v 查看当前的php版本信息

php -r (--run) 'echo "hello world!";' 可以直接在命令行执行php语句
注意 外面必须要单引号 不然会报错

php -l 路径/文件  检查php语法是否正确


直接表达式 切片
支持 echo [1,2,3][1];//值为2
支持 echo "string"[2];// r

新的加解密函数
password_hash
password_verify

## php5.6
废弃上下文调用 动态调用静态方法
建议使用(new A)->foo;进行更改

const 支持表达式定义 常量

支持可变参数的传递
```
function func1($a,$b,...$c){
    echo $a.'---'$b;
    print_r($c);
}
$a = [1,2,3,4,5,6];
func1($a);//1---2 
//c=[3,4,5,6];
```
```
function func2($a,$b){
    echo $a.'----'.$b;
}
$arr = [1,2,3,4,5,6];
func2(...$arr);
//结果是 1----2
```
pow(2,10);//1024 
echo 2 ** 10; //1024
```
namespace A{
	define("ACG", 18);
	const AGE = 20;
}
namespace B{
	echo ACG;
	echo \A\AGE;
	use const A\AGE;
	echo AGE;
}
}
```
## php7.0
#### 废弃说明
1. 对变量、属性和方法的间接调用现在将严格遵循从左到右的顺序来解析

而不是之前的混杂着几个特殊案例(**==就近原则==**)的情况

表达式	
PHP 5 的解析方式
PHP 7 的解析方式
```
$$foo['bar']['baz']
${$foo['bar']['baz']} //php5
($$foo)['bar']['baz'] //php7


$foo->$bar['baz']
$foo->{$bar['baz']}	//php5
($foo->$bar)['baz'] //php7

$foo->$bar['baz']()	
$foo->{$bar['baz']}() //php5
($foo->$bar)['baz']() //php7

Foo::$bar['baz']()	
Foo::{$bar['baz']}() //php5	
(Foo::$bar)['baz']() //php7
```
2. list的使用
```
list($a[], $a[], $a[]) = [1, 2, 3];
var_dump($a);

array(3) {
  [0]=>int(3)
  [1]=>int(2)
  [2]=>int(1)
}//php5
array(3) {
  [0]=>int(1)
  [1]=>int(2)
  [2]=>int(3)
}//php7
//PHP 5 里，list() 从最右边的参数开始赋值；
//PHP 7 里，list() 从最左边的参数开始赋值。
//官方表示赋值顺序可能还会更改,建议不要使用list()进行顺序的赋值
//list() 表达式不再可以完全为空。
list() =$a;//会报错
// list() 不能对字符串起作用
list($bar) = "abc";
var_dump($bar); // NULL php7
$bar[a,b,c];//php5
```
3. global 只接受简单变量
```
function f() {
    global $$foo->bar;//php5 支持可变变量

    global ${$foo->bar};//php7支持直接用 但是可以模拟
}
```
4. foreach的改变

foreach不再改变内部数组指针

在PHP7之前，当数组通过 foreach 迭代时，数组指针会移动。

实际上是一个(多了模拟数组的操作)而不影响原本的数组指针

foreach 通过值遍历时，操作的值为数组的副本
```
$array = [0, 1, 2];
foreach ($array as &$val) {
    var_dump(current($array));
}
int(1)int(2)bool(false)//php5
int(0)int(0)int(0)//php7

//引用迭代
$array = [0];
foreach ($array as &$val) {
    var_dump($val);
    $array[1] = 1;
}

//php5
int(0)
//php7
int(0)
int(1)
```
4. 十六进制字符串不再被认为是数字
```
var_dump("0x123" == "291");
var_dump(is_numeric("0x123"));
var_dump("0xe" + "0x1");
var_dump(substr("foo", "0x1"));

//php5
bool(true)
bool(true)
int(15)
string(2) "oo"

//php7
bool(false)
bool(false)
int(0)
Notice: A non well formed numeric value encountered in /tmp/test.php on line 5
string(3) "foo"
```
5. 移除mssql/mysql扩展 建议使用pdo

### 新增特性
1. 标量声明
//矢量 数组 对象 接口 php5中能申明的强制类型信息
//php7 能声明 int float bool string
```
function func1(int $a,int $b){
    echo $a+$b;
}
func1('a',1);//致命错误
func1('3',1);//31
declare(strict_types = 1);//开启严格模式
func1('3',1);//致命错误
//但是 int和float可以转换
```
2. 返回值声明
```
function func2(int $a,int b):int{
    retrun $a+$b;
}
```
3. null合并运算符
```
$age = $_GET['age']??$_GET['age'];
$age = $_GET['age']??$_GET['age']??$_POST['age'];
```
4.太空船操作符（组合比较符）
```
//1 2 -1
//1 1 0
//2 1 1
1 <=> 2 //-1
```
5. 通过 define() 可以定义常量数组
6. 匿名类
```
$obj = new class{
    public $name ="ok";
public function say(){
    echo $this->name;
}
};

echo $obj->say();

//匿名类的一个使用demo
interface tips{
    public function say();
}

class Myclass{
    private $tips;
    
    public function getTips(){
        return $this->$tips;
    }
        
    public function setTips(Inter $obj){
        $this->tips = $obj;
    }
}

//写法一
class M implements tips(){
    public function say(){
        echo "我是say方法";
    }
}
$obj = new Myclass();
$obj->setTips(new M());
$obg->getTips->say();//"我是say方法"
//写法二
$obj = new Myclass();
$obj->setTips(new class implements tips{
    public function say(){
        echo "我是say方法";
    }
})
$obj->getTips->say();
```
7. Unicode转义语法
```
$str = "\u{50}";//只能用双引号
echo $str;//p
```
8.Closure::call()//冒充
```
$num = 1;
$fun = function() use($num){
    echo "匿名函数".$num;
};
$num = 2;
echo $fun();//匿名函数1 不会变成2 因为先声明后使用

clsaa My(){
    private function say(){
        echo "111";
    }
}

$fun = function(){
    $this->say();
};
$fun->call(new My(),'say');//可以调用别的类的私有方法
```

9. unserialize 序列化
//可以允许指定的类序列化
```
可以将类进行序列化后存入内存

下次调用反序列化直接使用
```
10. assert 断言 预期
11. 批量导入命名空间
12. yield 支持返回值
```
//闭包 加yield生成器
$func = (function(){
    yield 1;
    yield 2;
    yield 3;
    return "这里是返回值";
})();
foreach($func as $item){
    echo $item;
}

echo $func->getRetrun();
```
13. 整除问题
```
echo intdiv(3,10);//0
echo intdiv(10,3);//3
```
## 7.1更高
1. void
```
function test ():viod{
    echo "无返回值";
}
```
2.