- linux的基本层次
    -
    - 外围应用程序-->命令解释器-->系统核心-->硬件
    
- 文件和目录管理
    - 
    - 创建空目录 `mkdir -m`(权限) `-p`(递归创建)
    - 删除空目录 `rmdir -p` (递归删除)
    - 删除非空目录：`rm -rf` (目录名)
    - 移动：`mv` (可以用于rename) `-b`(若覆盖会先备份)
    - 复制：`cp` (复制目录：`cp -r` )
    - `touch` (创建一个新的空文件)
- 查看
    - 
    - 显示当前目录下的文件 ls
    - 按时间排序，以列表的方式显示目录项 ls -lrt
    - `ls -l     ls -a`
    - 查看文件内容 cat
    - `cat a.text`
    - `cat a.text | more `控制输出的内容的大小
    - `cat a.text | less `
- 权限
    - 
    - 改变文件的拥有者 chown
    - 改变文件读、写、执行等属性 chmod
    - 递归子目录修改： chown -R 755 a/
    - 增加脚本可执行权限： chmod +x script_name
- 管道和重定向
    - 
    - `&`  表示任务在后台执行，如要在后台运行php脚本,则有  `php clean.php &`
    - `&&` 表示前一条命令执行成功时，才执行后一条命令 ，如 `echo '1' && echo '2'`
    - `|` 表示管道，上一条命令的输出，作为下一条命令参数，如 `echo 'yes' | wc -l`
    - `||` 表示上一条命令执行失败后，才执行下一条命令，如 `cat nofile || echo "fail"`
    - `;` 不管前一句是否执行成功均会执行后一句。如 `cd 1/ && echo '2'`

- 文件查找 find
    - 
    - -name 按名字查找
    - -type 按类型
    - -atime 访问时间
```
find . -atime(天) -7 -type f -print//列出7天内被访问的文件
find . -type d -print  //列出当前文件夹下的所有目录
find / -name "hello.c" 查找hello.c文件
```

- 排序 sort
    -    
    - -n 按数字进行排序 VS -d 按字典序进行排序
    - -r 逆序排序
    - -k N 指定按第N列排序
```
sort -nrk 1 data.txt
sort -bd data // 
忽略像空格之类的前导空白字符
```
- 去重uniq
    - 消除重复行
    - sort unsort.txt | uniq
- 统计 wc
    - 
    - wc -l file // 统计行数
    - wc -w file // 统计单词数
    - wc -c file // 统计字符数


- 磁盘管理
    - 
    - 查看磁盘空间利用大小
    - df -h 
    - 查看当前目录所占空间大小
    - du -sh
- 打包和解压
    -    
    - 在linux中打包和压缩和分两步来实现的
    - tar、zip命令
    - 打包是将多个文件归并到一个文件:
    - tar -cvf etc.tar /etc <==仅打包，不压缩！
    - gzip demo.txt #压缩
    - zip -q -r html.zip /home/Blinux/html #打包压缩成zip文件
    - 解压
    - `tar -zxvf xx.tar.gz -C(特定目录)`
    - `unzip test.zip # 解压zip文件`
```
tar -cvf log.tar log2012.log    仅打包，不压缩！ 
tar -zcvf log.tar.gz log2012.log   打包后，以 gzip 压缩 
tar -jcvf log.tar.bz2 log2012.log  打包后，以 bzip2 压缩 
```
- 常用命令
    -
- 系统安全
    - sudo su chmod setfacl
- 进程管理
    - w top ps kill pkill patree killall
- 用户管理
    - id usermod useradd groupadd userdel
- 文件系统
    -  mount umount fsck df du
- 系统重启
    - shutdown reboot
- 网络应用
    - curl telnet mail elinks
- 网络测试
    - ping netstat host
- 网络配置
    - homename,ifconfig
- 常用工具
    - ssh screen clear who date
- 软件包的管理
    - yum rpm apt-get
- 文件查找
    -locate ,find
- 文件内容查看
    - head tail less more
- 文件处理
    - touch unlink rename ln cat
- 目录操作
    - cd mv rm pwd tree cp ls
- 文件权限属性
    - setfacl chmod chown chgrp
- 文件传输
    - ftp scp
- 压缩解压缩
    - bzip2/bunzip2
    - gzip/gunzip
    - zip/unzip
    - tar
- 定时任务
    -
- crontab(循环)
    - crontab -e ***** (命令)
- at(只执行一次)
    - at 2:00 tomorrow
    - at> /home/clean.sh
    - at> ctrl+d
    - //明天两点执行clean脚本
- vi/vim
    -
    - 模式 一般/编辑/命令行
    - 一般模式: 删除,复制,粘贴
    - 切换编辑模式 iIoOaArR
    - 查找替换 /word 
    - 删除复制
    - xX dd ndd yy nyy p P ctrl+r
- shell 基础
    - 
    - 赋予权限 直接执行 chmod +x test.sh && ./test.sh
    -  调用解释器来执行脚本 bash,csh,ash,bsh,ksh
    - source ./test.sh
    - 开头使用 #!/bin/sh
    -  
```shell
每天零点重启服务器
crontab -e
00*** /bin/reboot
```

- `passwd` `userName` 修改密码