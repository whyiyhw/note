---
title: local ContOS7 build php dev envs
date: 2019-05-09 12:17:23
categories: docker nginx
tags: 
- php
---

## ContOS7 php 多环境的配置

### 使用最小化安装之后的第一个问题，内外网不通
  
- `vi /etc/sysconfig/network-scripts/ifcfg-ens33`
- 修改 `ONBOOT=yes` 后 `systemctl restart network.service`
- 重启主机，如果此时内外网通了但是 `yum list` 失败，主要是服务不可达，考虑为 `DNS` 的原因
- 修改 `vi /etc/resolv.conf` 修改 `dns` 为 `nameserver 8.8.8.8`
  
  ```shell
   yum update # 成功
   yum install wget -y # 装一个下载工具
   mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup # 备份
   wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-7.repo # 添加阿里源
   yum clean all # 清除缓存
   yum makecache # 生成缓存
   yum update -y # 更新

  ```

## 一些准备工作
  
- `yum install vim -y && yum install net-tools -y`   习惯了 `vim` 加上一个网络分析小工具
- `yum install gcc gcc-c++ httpd-tools autoconf pcre pcre-devel make automake -y` 安装一些编译测试工具
- `systemctl stop firewalld.service && systemctl disable firewalld.service` 关闭并禁用 防火墙
- `vim /etc/selinux/config`
- 修改 `SELINUX=disabled` 千万不要改错位置  
- 下面的 `selinuxtype` 要是改成 `disable` 建议删了重装会快一些
- 不然 `selinux` 可能会导致你的网络端口不可访问

## 安装 nginx

- [参考](http://nginx.org/en/linux_packages.html#RHEL-CentOS)
- `vim /etc/yum.repos.d/nginx.repo` 创建 `yum` 源文件
- 根据官方提示修改为
  
  ```conf
  [nginx]
  name=nginx repo
  baseurl=http://nginx.org/packages/centos/7/$basearch/
  gpgcheck=0
  enabled=1
  ```

- `yum list|grep nginx` 能够看到 `nginx`
  
  ```shell
    [root@localhost ~]# yum list|grep nginx
    nginx.x86_64                                1:1.16.0-1.el7.ngx         nginx
    nginx-debug.x86_64                          1:1.8.0-1.el7.ngx          nginx
    nginx-debuginfo.x86_64                      1:1.16.0-1.el7.ngx         nginx
    nginx-module-geoip.x86_64                   1:1.16.0-1.el7.ngx         nginx
    nginx-module-geoip-debuginfo.x86_64         1:1.16.0-1.el7.ngx         nginx
    nginx-module-image-filter.x86_64            1:1.16.0-1.el7.ngx         nginx
    nginx-module-image-filter-debuginfo.x86_64  1:1.16.0-1.el7.ngx         nginx
    nginx-module-njs.x86_64                     1:1.16.0.0.3.1-1.el7.ngx   nginx
    nginx-module-njs-debuginfo.x86_64           1:1.16.0.0.3.1-1.el7.ngx   nginx
    nginx-module-perl.x86_64                    1:1.16.0-1.el7.ngx         nginx
    nginx-module-perl-debuginfo.x86_64          1:1.16.0-1.el7.ngx         nginx
    nginx-module-xslt.x86_64                    1:1.16.0-1.el7.ngx         nginx
    nginx-module-xslt-debuginfo.x86_64          1:1.16.0-1.el7.ngx         nginx
    nginx-nr-agent.noarch                       2.0.0-12.el7.ngx           nginx
    pcp-pmda-nginx.x86_64                       4.1.0-5.el7_6              updates  
  ```

- `yum install nginx -y` 安装 `nginx`
- `systemctl enable nginx` 开机自启 `nginx`
- `systemctl start nginx` 启动 `nginx`
- 然后输入 `IP` 到浏览器访问成功
- 默认配置位置 `/etc/nginx/nginx.conf` `/etc/nginx/conf.d/default.conf`

## 安装 PHP

- [参考](https://webtatic.com/packages/php72/)

  ```shell
  yum install epel-release
  rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
  ```

- 安装 `php` 全扩展 `yum install mod_php72w php72w-bcmath php72w-cli php72w-common php72w-dba php72w-devel php72w-embedded php72w-enchant php72w-fpm php72w-gd  php72w-imap php72w-interbase php72w-imap php72w-intl php72w-ldap php72w-mbstring php72w-mysqlnd php72w-odbc php72w-opcache php72w-pdo php72w-pdo_dblib php72w-pear php72w-pecl-apcu php72w-pecl-imagick php72w-pecl-mongodb php72w-pgsql php72w-phpdbg php72w-process php72w-pspell php72w-recode php72w-snmp php72w-soap php72w-sodium php72w-tidy php72w-xml php72w-xmlrpc -y`
- 配置文件位置 `/etc/php.ini`
- `php -m` 就可以查看装了哪些扩展 嗯 基本都开了
- `systemctl start php-fpm` 虽然是一台机器但我们还是用 `php-fpm` 模式,便于后期加入 `docker` 的扩展 也可以使用 `.sock` 的模块加载模式 理论上会快一些
- `systemctl enable php-fpm` 开机自启

- 发现一个坑，并没有 `redis` 扩展 原生进行编译扩展
  - `wget http://pecl.php.net/get/redis-4.2.0.tgz`
  - `tar zxvf redis-4.2.0.tgz`
  - `cd redis-4.2.0`
  - `/usr/bin/phpize`(这个根据 `phpize` 实际情况来)
  - `./configure --with-php-config=/usr/bin/php-config`(这个根据 `php-config` 实际情况来)
  - `make && make install`
  - `vim /etc/php.d/redis.ini` 这个根据实际情况去决定 是改 `php.ini` 还是别的什么
  - 写入 `extension=gd.so`
  - `systemctl restart php-fpm` 就 ok 了

## php与nginx 链接起来

  ```shell
  [root@localhost ~]# cd dev
  [root@localhost dev]# mkdir -p www/php/test/
  [root@localhost dev]# cd www/php/test/
  [root@localhost test]# echo "<?php phpinfo();" >> ./index.php
  ```

- `vim /etc/nginx/conf.d/default.conf`
  
  ```shell
  location / {
        root   /dev/www/php/test;
        index index.php  index.html index.htm;
    }

    location ~* \.php$ {
        fastcgi_index   index.php;
        fastcgi_pass    127.0.0.1:9000;
        include         fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /dev/www/php/test$fastcgi_script_name;
    }
  ```

- `nginx -t && nginx -s reload`

## 使用docker 构建 php5.4 环境
  
- 更换镜像，使用阿里云的镜像加速
- `https://cr.console.aliyun.com/#/accelerator`
- `vim /etc/docker/daemon.json`
- 修改成自己的镜像加速
- `"registry-mirrors": ["https://xxxxx.mirror.aliyuncs.com"]`
- `systemctl daemon-reload`
- `systemctl restart docker`
- 运行生成一个 php5.4 的容器

  ```shell
  docker run -d \
  -v /dev/www/php/:/var/www/html \
  -p 9001:9000 \
  --name phpfpm54 --privileged=true php:5.4-fpm
  ```

## docker 中 扩展安装

- `docker exec -ti phpfpm54 /bin/bash`
- `-ti` 打开图形界面
- `/bin/bash` 执行 这个命令
- `exec` 可以在 不 `exit` 的情况下进行附加操作
- 进去后需要 `apt-get update` 里面是 `debian9` 系统
- `docker-php-ext-install pdo_mysql` 来装 `pdo_mysql` 扩展
- 以下这些扩展都可以安装 不过好多有坑0.0

```shell
bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zend_test zip
```

### docker Debian 换源安装 gd 库

```shell
cd /etc/apt && cp sources.list ./sources.list.bak
apt-get install vim

vim sources.list
deb http://mirrors.163.com/debian/ stretch main non-free contrib
deb http://mirrors.163.com/debian/ stretch-updates main non-free contrib
deb http://mirrors.163.com/debian/ stretch-backports main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch-updates main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch-backports main non-free contrib

//如果连apt-get update都做不到   可以先 rm sources.list 再创一个新的
echo "deb http://mirrors.163.com/debian/ stretch main non-free contrib
deb http://mirrors.163.com/debian/ stretch-updates main non-free contrib
deb http://mirrors.163.com/debian/ stretch-backports main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch-updates main non-free contrib
deb-src http://mirrors.163.com/debian/ stretch-backports main non-free contrib" >> sources.list
//将源更换为上述  新的源

apt-get update
apt-get install libfreetype6-dev -y
apt-get install libpng-dev -y
apt-get install libjpeg62-turbo-dev -y
//如果源没出问题 上述应该都能安装
docker-php-ext-configure gd --with-freetype-dir=/usr/include --with-jpeg-dir=/usr/include
//先编译gd库的扩展
docker-php-ext-install gd
// 这样就在docker容器内完美安装了gd库
```

- 装 redis 扩展

```shell
curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/3.1.3.tar.gz

tar xfz /tmp/redis.tar.gz

rm -r /tmp/redis.tar.gz

mkdir -p /usr/src/php/ext

mv phpredis-3.1.3 /usr/src/php/ext/redis

docker-php-ext-install redis
```

- 退出测试
- `vim /etc/nginx/conf.d/default.conf`
- 修改 `fastcgi_param SCRIPT_FILENAME /var/www/html/test$fastcgi_script_name;`
- 这里由于是在容器内执行所以修改路径为这个
- 设置 `phpstrom` 的 `sftp` 自动上传到 本地虚拟机

- ps aux|grep php

```shell
1 root 0:00 php-fpm: master process (/usr/local/etc/php-fpm.conf)
7 www-data 0:00 php-fpm: pool www
8 www-data 0:00 php-fpm: pool www

# 发现id 1就是php了，给它个信号让它重启即可，执行命令如下：
kill -USR2 1
```

- 如果是在外面也可以：docker exec -it 容器id或名称 kill -USR2 1
- 或者重启容器  docker restart 容器id或名称

## 安装 swoole

- `yum install git -y`
- `git clone https://gitee.com/swoole/swoole.git`
- `wget https://github.com/redis/hiredis/archive/v0.14.0.zip`
- `yun install -y unzip`
- `unzip v0.14.0.zip`
- `cd hiredis-0.14.0`
- `make -j`
- `yum install openssl-devel`

```shell
./configure \
--with-php-config=/usr/bin/php-config \
--enable-openssl  \
--enable-http2  \
--enable-async-redis \
--enable-sockets \
--enable-mysqlnd && make clean && make && sudo make install
```

```shell
cd /etc/php.d/
cp sockets.ini ./swoole.ini
ls
vim swoole.ini 修改为 swoole
systemctl restart php-fpm
```

```shell
[root@localhost swoole]# php --ri swoole

swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.4.0-alpha
Built => May 10 2019 20:30:06
coroutine => enabled
epoll => enabled
eventfd => enabled
signalfd => enabled
cpu_affinity => enabled
spinlock => enabled
rwlock => enabled
sockets => enabled
openssl => OpenSSL 1.0.2k-fips  26 Jan 2017
http2 => enabled
pcre => enabled
zlib => enabled
mutex_timedlock => enabled
pthread_barrier => enabled
futex => enabled
mysqlnd => enabled
async_redis => enabled

Directive => Local Value => Master Value
swoole.enable_coroutine => On => On
swoole.display_errors => On => On
swoole.use_shortname => On => On
swoole.unixsock_buffer_size => 8388608 => 8388608
```