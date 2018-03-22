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