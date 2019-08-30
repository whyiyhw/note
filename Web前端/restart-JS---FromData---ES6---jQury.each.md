ES6的新语法 HTML5 CSS3 这些东西都应该有些了解才行

```
document.querySelector()HTML5的新选择器 和jQuery的$()差不多
```

```
var myvar = 'my value';

(function() {
  console.log(myvar); // undefined
  var myvar = 'local value';
})();
```
实际的运行步骤
```
var myvar = 'my value';
(function() {
  var myvar;
  console.log(myvar); // undefined
  myvar = 'local value';
})();
```
let  和 var 的一个区别
let 是一个块级作用域 var 是一个全局的变量声明
```
if(true){
    let i = 5;
}
console.log(i)//报错  Uncaught 
```
模板语法

```
var some = 123;
console.log('test'+some *10);
console.log(`test${some * 10}`)//可用于复杂的字符拼接
```
**FormDat**对象，可以把form中所有表单元素的name与value组成一个queryString，提交到后台。在使用Ajax提交时，使用FormData对象可以减少拼接queryString的工作量。

创建一个FormData空对象，然后使用append方法添加key/value
```
var formdata = new FormData();  
formdata.append('name','xue');  
formdata.append('age',22);  
```
使用ajax进行提交
```
function fsubmit(){  
        var data = new FormData($('#form1')[0]); //拿数据就方便多了 
        $.ajax({  
            url: 'server.php',  
            type: 'POST',  
            data: data,  
            dataType: 'JSON',  
            cache: false,  
            processData: false,//设置 processData 选项为 false，防止自动转换数据格式  
            contentType: false //不使用默认的application/x-www-form-urlencoded
    //设置成 false是为了避免JQuery对其操作,上传时contentType=multipart/form-data
        }).done(function(ret){  
            if(ret['isSuccess']){  
                alert('提交成功');    
            }else{  
                alert('提交失败');  
            }  
        });  
        return false;  
    }  
```
```
使用 for(key in value){}进行对象的遍历
for(key in [1,2,3,5,4]){
		console.log(key);//0 1 2 3 4
		console.log(some[key]);//1 2 3 5 4
	}
```
```
使用jQuery进行一个对象的遍历
jQuery.each(object, [callback]) 

$.each( [1,2,3,4,5], function(key, value){
		 console.log( "Item #" + key + ": " + value)
	 });
按下按钮 将li标签内的内容进行弹出
$("button").click(function(){
  $("li").each(function(){
    alert($(this).text())
  });
});
```

```
$('div').each(function (i){  
   //i就是索引值  this 表示获取遍历每一个dom对象  
});  

$('div').each(function (index,domEle){  
  //index就是索引值  domEle 表示获取遍历每一个dom对象  
});  
//1.先获取某个集合对象  
//2）遍历集合对象的每一个元素  
var d=$("div");  
$.each(d,function (index,domEle){  
  //d是要遍历的对象  index就是索引值  domEle 表示获取遍历每一个dom对  
}); 
```