JS中的test是原来是JS中检测字符串中是否存在的一种模式，JS输入值是否为判断正整数代码： 

<script type=”text/javascript”> 
　　function test() { 
　　　　var num = document.getElementById(“num”).value; 
　　　　if (num==”") { 
　　　　　　alert(‘请输入内容’); 
　　　　　　return false; 
　　　　} 
　　　　if (!(/(^[1-9]\d*$)/.test(num))) { 
　　　　　　alert(‘输入的不是正整数’); 
　　　　　　return false; 
　　　　}else { 
　　　　　　alert(‘输入的是正整数’); 
　　　　} 
　　} 
</script> 

<html> 
<body> 
<input type=”text” id=”num” /> 
<input type=”button” value=”测试” onclick=”return test()” /> 
</body> 
</html> 

附判断数字、浮点的正则表达：
 
- ”^\\d+$” //非负整数（正整数 + 0）
- “^[0-9]*[1-9][0-9]*$” //正整数
- “^((-\\d+)|(0+))$” //非正整数（负整数 + 0）
- “^-[0-9]*[1-9][0-9]*$” //负整数
- “^-?\\d+$” //整数
- “^\\d+(\\.\\d+)?$” //非负浮点数（正浮点数 + 0）
- “^(([0-9]+\\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\\.[0-9]+)|([0-9]*[1-9][0-9]*))$” //正浮点数
- “^((-\\d+(\\.\\d+)?)|(0+(\\.0+)?))$” //非正浮点数（负浮点数 + 0）
- “^(-?\\d+)(\\.\\d+)?$” //浮点数