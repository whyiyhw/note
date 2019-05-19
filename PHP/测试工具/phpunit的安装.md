使用的php版本为5.5
```
    "require-dev": {
        "phpunit/phpunit": "^4.0",
        "phpunit/dbunit": "^2.0",
        "phpunit/phpunit-selenium": ">=1.2"
    }
```
composer直接安装到项目

下载对应版本的phar
```
https://phar.phpunit.de/phpunit-5.phar
```
将下下来的.phar文件重命名为phpunit.phar

执行
```
echo @php "%~dp0phpunit.phar" %* > phpunit.cmd  
```
将产生一个phpunit.cmd 文件

必须设定环境变量的目录为你 phpunit.phar 的目录 

才能在命令行使用 phpunit --version

我的是phpunit.phar --version 也能显示

PHPUnit 4.8.36 by Sebastian Bergmann and contributors.

不管怎样算是成功在项目中安装了phpunit