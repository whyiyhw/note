 func_get_arg()获取自定义函数中用户传递过来的所有参数
 ```
 function fun1(){
    $arr = func_get_arg();
 
     retrun $arr;
 }
 fun1([1,2,3,4,5]);
 //结果也是一样的数组
 ```
 compact()//作用和extract相反
 ```
 $s = 1;
 $d = 2;
 $a =['s','d'];
 print_r(compact($a));
 //得到['s'=>1,'d'=>2];
 ```
 
 extract() 数组转换变量
 ```
 extract(['id'=>1,'name'=>'ass']);
 echo $id.'---'.$name;//1---ass 相当于声明了两个变量
//name 为变量名 value 为变量值
 ```

# 数组
 - `array_chunk` 与 `array_slice`
```php
<?php
$arr1 = ["greet", "red", "yellow"];
$arr2 = ["blue", "block", "abandon"];

$arr1 = array_values($arr1);
print_r($arr1);//[0 => greet,1 => red,2 => yellow]

$arr2 = array_keys($arr2);
print_r($arr2);//[0 => 0,1 => 1,2 => 2]

$arr3 = array_combine($arr1, $arr2);
print_r($arr3);//[greet => 0, red => 1, yellow => 2]

$arr3 = array_flip($arr3);
print_r($arr3);//[0 => greet,1 => red,2 => yellow]

```