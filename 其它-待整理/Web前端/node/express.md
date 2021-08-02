## express  4.16.3
### 安装:
+ `npm init -y`
+ `npm install --save express`
+   `npm - -S express`简写

### hello world
```javascript
    var express = require('express')
    
    var app = express()
    
    //新写法
    app.get('/',(req.res)=> res.send('index'))
    
    //旧的写法
    app.get('/about',function(req,res){
         res.send('about us')
    })
    
    app.listen(3000,()=>console.log('app is running 3000 prot ...'))
```
### 热更新
`npm install global nodemon`

使用
`nodemon index.js`之后对文件的每一次保存 都会触发node重新 加载index.js

避免了手动去再次加载

### 基础路由
```javascript
app.METHOD(PATH, HANDLER)
app.get('/',function(req,res){
    res.send('this is home')
})
app.get|post|put|detele|(PATH, HANDLER)
```
### 静态路由

express.static(root, [options])
```
//允许访问index.js平级目录下的 public下的所有文件
app.use('/public/',express.static('./public/'))
//而不需要加上/public
app.use(express.static('public'))
//要使用多个静态资产目录，
//只需要express.static多次调用中间件函数
app.use(express.static('public'))
app.use(express.static('upload'))
//可以理解为路由的别名  实际的目录还是 public
//但是访问时候需要加上类似:
//http://localhost:3000/static/images/bg.png
app.use('/static', express.static('public'))
//可以使用不在node启动目录下的绝对路径
app.use('/static', express.static(path.join(__dirname, 'public')))
```

### 在express中 配置使用 art-template
安装
```shell
npm install --save art-template
npm install --save express-art-template
```
配置art-template
```
var express = require('express')

var app = express()

app.use(express.static('./public/'))

//配置使用art-template 模板引擎
//当渲染以 .art结尾的文件的时候,使用 art-template模板引擎
//约定  将模板文件放在 views目录下
//增加了 res.render方法 res.render('模板名',{模板数据})
//如果想要更改约定 路径 可以使用
//app.set('views',render函数的默认路径)
app.engine('html', require('express-art-template'));


app.get('/',(req,res) => res.render('404.html'))

app.get('/admin',
	(req,res) => 
	res.render('admin/index.html',{
	title: 'admin管理系统'
	})
)


app.listen(3000,() => console.log('app is running...'))
```
### 配置body-parser处理POST请求
```
npm install body-parser --save

var bodyParser = require('body-parser')

//parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }))

// parse application/json
app.use(bodyParser.json())

//req.body
```
### express处理 get
```javascript
app.get('/comment',function(req,res){
		var comment = req.query
		comment.dataTime = '2018-5-1'
		commonds.unshift(comment)
		res.redirect('/')
	    }
	)
	//req.query
```
### express crud

### callback hell 的解决promise
