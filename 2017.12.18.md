# php新特性
参数的标量声明

```
float int bool string
function ceshi(int $id){
    return $id    
}
```

返回值的声明 可以是标量和array、对象（classname）
```
function ceshi(array $id):array {
		return $id;
}

对象 的不一样 是把 后面的参数 作为前一个类名的 对象
class Welcome extends CI_Controller {
    public function index()
	{
		$id = $this;
		var_dump($this->ceshi($id));//123
	}
 	public $ceshiss = 123;
	public function ceshi(Welcome $id){
		return $id->ceshiss;
	}
}
```
null 合并运算符
```
之前的写法
$id = $_GET['id']?$_GET['id']:0;
现在可以简写
$id = $_GET['id']?? 0;
```
飞船运算符 用于比较两个表达式

```
echo 2.2<=>2.1;// 1
echo 2.0<=>2.0;// 0
echo 2.2<=>2.3;// -1
```
通过 define() 定义常量数组

```
define(SOME,[1,2,3]);
echo SOME[0];//1
```


php如何拿到一个空对象
```
$empty_object=(object)null;
```