# winodws下安装测试

使用的php版本为7.2

- composer.json

```json
    "autoload": {
        "classmap": [
            "src/"
        ]
    },    
	"require-dev": {
        "phpunit/phpunit": "^8",
    }
```
composer直接安装到项目

下载对应版本的phar
```
https://phar.phpunit.de/phpunit-8.phar
```
将下下来的.phar文件重命名为phpunit 

必须设定环境变量的目录为你 phpunit 所在的目录 

才能在命令行使用 phpunit --version

我的是phpunit.phar --version 也能显示

PHPUnit 8.3.4 by Sebastian Bergmann and contributors.
