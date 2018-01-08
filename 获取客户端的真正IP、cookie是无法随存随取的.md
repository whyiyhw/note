# 获取客户端的真正IP
如果我问你 php获取客户端IP使用什么 你的答案时$_SERVER['REMOTE_ADDR']

那这就值得你可以继续看下去

需求是拿到用户IP进行一个入库 之后会从数据库中取出进行一个判断

最开始我是用了CI框架的
```
$this->input->ip_address();
```
然后发现不对劲 这样拿到的全是自己服务器的IP

之后使用PHP自带的
```
$ip = $_SERVER["REMOTE_ADDR"];
```
能拿到 但是有一个问题 用户是直接访问你的服务器  但是中间会代理服务器的传递  所以拿到的也是阿里云的服务器IP

最后我使用了
```
  $ip = getenv('HTTP_CLIENT_IP');
//HTTP_CLIENT_IP是获取当前客户端的IP,如果你用代理服务器接访问的，
//它记录的就是代理服务器的IP,而不是真实的用户IP,
//而不管是代理还是直接访问,其IP都可以通过一些技术手段造假或是弄成unknown  

  $ip = getenv('HTTP_X_FORWARDED_FOR');
//专门记录当代理服务器访问的环境变量
//"HTTP_X_FORWARDED_FOR"会除掉代理服务器的IP，记录下当前真实的IP 

strcasecmp()二进制安全比较字符串（不区分大小写）。 

/**
* 公用方法 拿到用户真实IP
*/
public function get_client_ip(){
    $headers = ['HTTP_X_REAL_FORWARDED_FOR',
                'HTTP_X_FORWARDED_FOR', 
                'HTTP_CLIENT_IP',
                'REMOTE_ADDR'
                ];
    foreach ($headers as $h){
        $ip = $_SERVER[$h];
        // 有些ip可能隐匿，即为unknown
        if ( isset($ip) && strcasecmp($ip, 'unknown') ){
                break;
            }
        }
        if( $ip ){
            // 可能通过多个代理，其中第一个为真实ip地址
            list($ip) = explode(',', $ip, 2);//最多拿两个值
        }
        return $ip;
    }
```
getenv()//获取一个环境变量的值

# cookie是无法随存随取的

如果 你
```
setcookie('name','xiaosan');
echo $_COOKIE['name'];
```
结果会是一片空白

因为cookie默认是从客户端传递过来的 传过来的时候 没有name这个值 只有在当前脚本执行结束的时候 cookie被写入到客户端 

这个时候 你再去执行上述代码才会出现 xiaosan
 
 所以一段时间的 随存随取建议使用session