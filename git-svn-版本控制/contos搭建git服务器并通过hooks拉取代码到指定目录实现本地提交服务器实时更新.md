# centos 搭建可自动上传的服务

## 搭建git服务器

```shell
# yum install -y git
# cd /home
# mkdir -m 777 test
# cd test
# git init --bare test.git
```

权限问题 我是自己个人用就没有必要

## 在本地拉取git仓库

选定一个本地目录

```shell
# git clone ssh://root@123.123.123.123/home/test/test.git
```

ssh://用户名@主机地址/仓库路径

会让你输入所选角色的密码

克隆下来后 本地会有一个test的空文件夹

这个时候基本上 add commit push 三连就可以把文件推上去

### 重点在于hooks的使用

## 使用hook部署一个TP的博客项目

```
# cd /home/test/
# ls
test.git
# cd test.git/hooks/
# cp post-receive.sample post-receive
# vi post-receive
```

```
在最下面加上
#站点根目录
git --work-tree=/data/www/tp checkout -f
#因为tp的缓存文件在上传上去时需要执行权限 所以写一个简单的shell命令
/bin/chmod -R 777 /data/www/tp/Application/Runtime
```

```shell
#保存退出后
# chmod +x post-receive
```

在本地提交 tp项目到服务器

设置一下虚拟主机对应目录

阿里云安全策略端口开放

iptables端口开放

基本上项目就能本地更新 服务器上直接展示效果

### 最后再说点小东西 gitignore
很简单但是实用

比如说你本地的log和 cache不想提交到服务器 
又想用 git add .

这时候在.git 同级目录下增加一个.gitignore文件

*.log #所有的.log结尾的文件不会被add

./Application/Runtime/Cache/

#所有的编译缓存文件不会被add

#除了这些还可以指定哪个文件一定会被add
!./Application/Runtime/Cache/123.php

#上述的缓存中只有123.php会被 add