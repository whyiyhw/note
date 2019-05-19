# 进程
运行中的程序
php index.php 
进程    
1.有内存 
2.上下文环境

进程可以创建 多个子进程

子进程会复制父进程的内存和上下文环境

子进程和父进程的内存和变量  是相互独立的

父进程 有 $a =1
子进程 $a =1 改 $a= 2
子进程 $a =1 改 $a= 3
但是父进程的$a 不会随着子进程的改变而改变

变量不通用,父子进程的通讯怎么解决?  

方式1  
共享内存(索引->内存)
不属于任何进程 

任何进程通过 索引可以 访问

进程关闭 共享内存不会关闭

# ipcs -m 查看系统的共享内存

```
------ Shared Memory Segments --------
key        shmid      owner      perms      bytes      nattch     status 
名字       索引       用户       权限        大小
```

# swoole基本架构
第一层
Master   Main Reactor         -------->处理事件驱动
   Reactor Reactor Reactor    -------->Reactor会实例化一个对象来处理事件比如说 客户端的链接 断开 管道 异步操作的文件描述
第二层
Manager                       -------->用来管理 worker子进程 和task线程  不处理业务逻辑
第三层
Worker Worker Worker          -------->用来处理客户端的请求
Task  Task Task Task Task     -------->用来处理异步工作进程 耗时较长的任务

进程与进程的通讯 是通过管道来实现的
1). Main Reactor 线程 接受 客户端的链接请求--->将读写操作监听注册到 Reactor进程中 ---->通知Worker进行链接的回调
2). 客户端发送数据之后 Reactor 会将数据 发给 Worker(可以自己处理也可以发给)-->Task进程---处理完->Worker---通知->Reactor->客户端
3). worker 发生意外 时  manager进程会拉起一个新的Worker 进程 来保持 Worker 的数量一致


# Task 简介
独立于Worker工作进程  来处理耗时较长的任务 
所以 不会占用Worker进程 可以让worker去处理 客户端的请求  加强了并发能力

Worker进程调用 task()方法 把数据 传递给 Task进程去完成业务逻辑 Task 进程在完成逻辑之后
调用 finsh()方法 或者return 结果 给 Worker进程的onfinsh()方法
两个进程之间是通过管道 进行通讯

数据小于8K 管道直接传输
数据大于8K 会写入临时文件
Task 只能传递字符
task传递对象的时候 使用序列化传递一个对象的拷贝
但是 我们在Task进程中对于对象的改变并不能反馈给Worker进程

Task的onFinish回调只会返回给发起调用的Worker进程 也就是说 一旦发起回调的Worker 进程挂了  数据不会被混淆
