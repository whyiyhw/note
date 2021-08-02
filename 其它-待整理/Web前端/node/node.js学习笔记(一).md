node.js 也是一门用于服务器编程的语言

主要是一个提供了js运行的环境

分为系统自带的核心模块,和自己编写的模块

node是一个文件模块化的语言  

即 index.js 虽然可以 引入 a.js文件

a.js也会在引用时被执行

但是index.js 无法使用 a.js里面的函数或者 变量

文件间的通讯 是通过 exports对象 来实现

index.js = require('./a')  拿到的事 a.js里面的exports对象默认为{}

//这个模块如果只需要导出 固定的变量 或是方法

//那么就不需要挂载的方式直接
```
module.exports = 'hello'; //导出字符hello
```
---

不使用分号的代码风格
```
//以下三种情况
1. (

2. [

3. `
//开头的代码 需要加 ; 其它情况不需要加;
```
### Node.js的模块化
- 文件作用域
- 通信规则 1. 加载 require 2. 导出 exports

#### 加载

```javascript
var foo = require('模块')//自定义/系统/第三方
```
两个作用
1. 优先执行被加载模块中的代码
2. 得到被加载模块中的`exports`导出接口对象

#### 导出

1. Node中是模块作用域,默认文件中所有的成员只在当前模块有效--防止命名冲突,变量污染
2. 对于希望可以被其它模块访问的成员,我们需要将其挂载到`exports`对象中

导出多个成员('拿到对象')
```javascript
exports.a = 123
exports.b = 123
exports.c = function () {
    console.log('log')
}
```
导出单个成员('拿到字符串')
```javascript
module.exports = 'hello world'

module.exports = function () {
    console.log('log')
}
```
##### `exports`实际上是`module.exports`的一个引用

默认每个模块开始都会隐式声明 
```javascript
module{exports:{} ...} 
var exports = module.exports
``` 

结束时 会 `return module.exports`

所以 说`exports`变量是`module.exports`的一个引用

```
//意思是 
module.exports = 456//改变了关系 exports失效了
exports = 123 //引用改变了其引用关系
exports = module.exports//456
exports.foo = 123//这个声明实际上对module.exports没有生效
//因为此时exports是对字符的一个引用 .foo不成立
//那么你导出拿的值 还是456
```
- jQuery 的each 和原生 JavaScript 方法里的forEach
  + EcmaScript 5 提供 forEach
    * 不支持IE8
  + jQuery的each由 jQuery 第三方库提供
    * jQuery1.11是兼容IE8的
- 301和302的区别
    + 301 永久重定向 浏览器会记住（下次直接请求新地址）
    + 302 临时重定向 浏览器不会记住

- `require（）`的使用
    + require('./b')
    + require('./b')
        * 两次加载只会加载一次 优先走缓存
        * 会拿到exports 对象但不会执行代码
    + 引入第三方包的流程
    + 1. node_modules/jquery
    + 2. node_modules/jquery/package.json
    + 3. node_modules/jquery/package.json 里面的 main 属性
    + main属性记录jquery实际的入口文件 
    + 先解决依赖 然后开始加载
        * 如果json文件没有main(或是没有json文件)
        * 会默认加载当前目录下的index.js
        * 如果依然没有 会向上一直找到根目录下
        * 所有的node_modules目录
### npm
    1. 常用命令
+ `npm init -y` 跳过引导,快速生成package.json
+ `npm install` 一次性安装 dependencies中的依赖
+ `npm install` 包名
+ `npm install --save ` 包名
    * `npm i -S`包名   简写
+ `npm uninstall `包名
    * 只删除包名 不删除 依赖
+ `npm uninstall --save `包名
    * 删除的同时也会把依赖也去除
    * npm un -S 包名
+ `npm i --global npm`更新本身
### cnpm与npm
+   `npm install --global cnpm`是 安装cnpm包
```
npm config set registry https://rehistry.npm.taobao.org
//更改配置为从淘宝镜像安装

npm config list
"npm config ls -l" to show all defaults config.
```
