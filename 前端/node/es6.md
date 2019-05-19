### 数组操作
+ find
+ findIndex
+ foreach
+ every
+ some `[1,2,3].some(function(item){return item === 3}) true`

+ includes `[1,2,3].includes(3) true`

+ indexOf `[1,2,3].indexOf(3) 2 未找到返回-1`

+ map

+ reduce `[1,2,3].reduce(function(prev,curr) { return prev+curr }) 6`

- javascript 模块化
    + Node中的 CommonJS
    + 浏览器中的
        * AMD require.js
        * CMD sea.js
    + EcmaScript 6 以支持但大部分浏览器还未支持
    + 可以使用编译工具进行转换

### MongoDB
mongod --version
mongo 
- show dbs
  + 查看所有
- use dbname
  + 切换到指定db 没有就新建
- db 
  + 查看当前操作db
