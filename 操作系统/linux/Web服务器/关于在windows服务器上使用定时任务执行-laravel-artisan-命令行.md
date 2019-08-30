---
title: 关于在windows服务器上使用定时任务执行 laravel artisan 命令行
date: 2019-05-07 21:54:51
categories: php
tags: 
- bat
- laravel
---
# 关于在windows服务器上使用定时任务执行 laravel artisan 命令行
- 需解决的问题
    - 公司windows 服务器上 的 laravel 项目需要定时去更新一批数据（频率不高）

- 解决思路 
    - 1. 可以使用 golang 去完成 更新数据的操作，编译打包成 exe 文件 用windows 自带的计划任务去处理
    - 2. 对外提供一个 url 接口，在外部服务器上定时去 curl 请求
    - 3. 使用 windows 自带的 .bat 脚本加计划任务，配合larave artisan 命令行去处理

- 实际方案
    - 考虑了生产环境比较封闭，采取第三种方式去处理

- bat 脚本的编写
    - `cd /d D:\path\to  php artisan queue:check`
    - 因为我已经将 php.exe 写入环境变量 所以可以直接在 test.bat 中这么写
    - 如果为独立的 php 脚本文件，也是一样的写法

- laravel 命令行的编写
    - `php artisan make:command QueueCheck`
    - 然后去对应的文件下面去修改与完成逻辑代码即可

- windows 计划任务
    - win+R 输入 taskschd.msc 即可使用图形化的计划任务表
    - 新增计划任务，选择执行频次与触发时机，最后要记得改为管理员权限去执行，
