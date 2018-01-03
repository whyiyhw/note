```
switch (0) {
	    case 'test1':
	        echo 1;
	    case 'test2':
	        echo 2;
	    case 'test3':
	        echo 3;
	        break;
			}// 1 2 3 
```
## switch使用的是==比较 所以会有一个类型转换
### php强制保留两位小数
今天的任务需要 我将一个整数或者是小数或者是0 强制都保留两位小数
```
echo round(0,2);//0
echo number_format(0,2,'.','');//0.00
```
很显然 round()并不能满足需求 且如果传入的是字符串会报错

要是想要保留两位有效数字 number_format()更适合

number_format() 只接受1,2或者4 位参数 就是 说 3和4参数必须一起写 3参数一般使用. 4参数值就可以为‘’
### laravel中的路由分类
```
//路由中输出视图
Route::get('/', function () {
    return view('welcome');
});
//基本路由
Route::get('basic',function(){
  return 'Hello World!';
});
Route::post('basic1',function(){
  return 'Hello World!';
});
//多请求路由
Route::match(['get','post'],'some',function(){
  return 'some';
});
Route::any('some1',function(){
  return 'some1';
});
//路由参数
Route::get('user/{id}',function($id){//必选
  return 'res-id-'.$id;
});
Route::get('user/{name?}',function($some = null){//可选 需要给默认值 后面的变量名不一定要和前面的相同
  return 'res-name-'.$some;
});
Route::get('user/ip/{ip}',function($ip){
  return 'res-ip-'.$ip;
})->where('ip','\d+');//路由参数的正则约束
Route::get('user/ip/{ip}/name/{name}',function($ip,$name){
  return 'res-ip-'.$ip."-name-".$name;
})->where(['ip'=>'\d+','name'=>'\w+']);//多路由参数的正则约束使用数组
//路由别名
Route::get('user/as/meber-center',['as'=>'center',function(){
  return route('center');//好处是即使路由被改了 还是可以返回正确的路由
//  return 'member-center';
}]);
//路由群组
Route::group(['prefix'=>'members'],function(){//这是加前缀
  Route::get('user/id',function(){
    return 111;
  });
  Route::get('user/ip',function(){
    return 222;
  });
});
```