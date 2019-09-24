---
title: 关于composer的一些记录
date: 2019-05-15 10:33:38
categories: php后端
tags:
- composer
---

- 安装

```shell
php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

- `composer dump-autoload -o`

```shell
Compsoer\ClassLoader 会优先查看 autoload_classmap 中所有生成的注册类。
如果在classmap 中没有发现再 fallback 到 psr-4 然后 psr-0

所以当打了 composer dump-autoload -o 之后，
composer 就会提前加载需要的类并提前返回。
这样大大减少了 IO 和深层次的 loop
```

### 问题点 `You made a reference to a non-existent script @php artisan package:discover`

```shell
composer -V
Composer version 1.2.1 2016-09-12 11:27:19
```

- 解决方法，升级 composer 版本
- `composer selfupdate`

```shell

Updating to version 1.6.3 (stable channel).
    Downloading: 100%
Use composer self-update --rollback to return to version 1.2.1
```