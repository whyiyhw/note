### Swap file ".ceshi.c.swp" already exists!
vi server.php 中途断电一次 再次进入就出现此提示

产生原因

打开一个文件，vi就会生成这么一个.(filename)swp文件以备不测，如果你正常退出，那么这个.(filename)swp文件将会自动删除。

(filename)swp文件就是你没有正常退出vi或者vim编辑器时留下来的！

解决方法

rm server.php.swp就可以了

### unset,array_splice删除数组中元素的区别
```
unset 删除 索引不会重新排序
但是array_splice($arr,1,1);//从索引1开始删除一位
数组会被重新排序
array_splice 还可以向任意位置插入值
array_splice($arr.1,0,'test'),此时索引为1的位置是test

```
### 删除数组中特定元素
```
array_keys($arr,2);//找到值为2的下标的所有数组
foreach unset();
实际上只有在确定值唯一的情况下好用
不然 还不如foreach

array_filter ( array $input [, callable $callback = "" ] )
很神奇的函数 如果没有第二个参数值为false的全被排除
第二个参数为一个回调函数 以函数的返回值是否为false来判断
此值能否被留住  返回值为过滤之后的数组
```
### vim ctrl+s锁定 之后 需要ctrl+q 退出

### netstat -tunpl 查看端口被占用信息

### ps auxf |grep php 查看所有php相关的master 和子进程

### kill、killall、pkill
```
加 -9 是因为有些进程不会理会kill命令 -9 强制执行
kill -9 9501 //杀死 Pid为9501的进程 
killall -9 php //杀死php相关的所有进程
pkill -9 终端号 踢出终端  root用户特权

```
# 学到现在才发现PHP单线程的局限性
# 虽然不用去理会 线程安全,内存也不用管理
# 可是一旦用户量起来了 就只能换语言实现并发 
# swoole 解决了很多问题 但还是有大量的坑被埋下
# 眼界开阔 学无止境 只会一门语言,就像是手里只有锤子
# 看什么都像钉子 从今天开始 目标是明年的今天,工资翻倍