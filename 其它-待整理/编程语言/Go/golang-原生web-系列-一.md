---
title: golang 原生web 系列(一)
date: 2019-02-14 15:35:34
categories: golang Web
tags: 
- web
- golang
---
# 原生 golang 构建 web 服务
```go
package main

import (
	"fmt"
	"log"
	"net/http"
	"strings"
)

func sayHello(w http.ResponseWriter, r *http.Request) {
	r.ParseForm()       // 解析参数 默认不会解析参数
	fmt.Println(r.Form) // 输出参数
	fmt.Println(r.Form["url_long"])
	for k, v := range r.Form {
		fmt.Println("key :", k)
		fmt.Println("value :", strings.Join(v, ""))
	}
	write := "访问方法是:" + r.Method + "<br />"
	write += "请求变量是:" + r.URL.Path
	fmt.Fprintf(w, write) // 这个写入到客户端
}

func main() {
	http.HandleFunc("/", sayHello) // 设定访问路由
	if err := http.ListenAndServe(":9090", nil); err != nil {
		log.Fatal("ListenAndServer : ", err)
	}
}

```
# 原生 php 构建web 服务
```php
<?php
$_SERVER = array();
//创建一个tcp套接字,并监听8088端口
if ($web = stream_socket_server('0.0.0.0:9090', $errno, $errstr)) {
    while (true) {
        $conn = @stream_socket_accept($web);
        if ($conn) {
            $_SERVER = array();
            decode(fgets($conn));
            fwrite($conn, encode("访问方法是:" . $_SERVER['REQUEST_METHOD'] . "\n<br />请求变量是:" . $_SERVER['QUERY_STRING']));
            fclose($conn);
        }
    }
} else {
    die($errstr);
}

//http协议解码
function decode($info)
{
    global $_SERVER;
    list($header,) = explode("\r\n\r\n", $info);
    //将请求头变为数组
    $header = explode("\r\n", $header);
    $data = explode(' ', $header[0]);
    if ($data[0]) {
        list($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_PROTOCOL']) = $data;
        $_SERVER['QUERY_STRING'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    } else {
        $_SERVER['REQUEST_METHOD'] = '';
        $_SERVER['QUERY_STRING'] = '';
    }
}

//http协议加密
function encode($str)
{
    $content = "";
    $content .= "HTTP/1.1 200 OK\r\n";
    $content .= "Server: php_web/1.0.0\r\n";
    $content .= "Content-Type:text/html;charset=utf-8\r\n";
    $content .= "Content-Length: " . strlen($str) . "\r\n\r\n{$str}";
    return $content;
}
```

### 说明
  - 从以上的两种构建方式,可以看出 php 和 go 的原生对于 web 服务的封装
  - golang 很显然 是 http包 对 请求进行了封装,但是 底层两者在处理请求上并没有什么区别
### 对于web服务器的分析
  - 大致可以抽象为 一下的四点
    - Request：用户请求的信息，用来解析用户的请求信息，包括post、get、cookie、url等信息
    - Response：服务器需要反馈给客户端的信息
    - Conn：用户的每次请求链接
    - Handler：处理请求和生成返回信息的处理逻辑
  - 我们所有的工作 都是 对于上面四点的封装
  - 从简易的 php web 服务器 构成来看
    - 如何监听端口?
    - 如何接收客户端请求?
    - 如何分配handle?
    - 这三点 是我们要处理的
  - golang 使用了 http.ListenAndServe() 来实现端口监听
    - 根据源码可知, 底层就是初始化一个server对象，然后调用了 net.Listen("tcp", addr) ，也就是底层用TCP协议
搭建了一个服务，然后监控我们设置的端口。
```golang
# 第一步 构建了一个Server结构体的引用
func ListenAndServe(addr string, handler Handler) error {
	server := &Server{Addr: addr, Handler: handler}
	return server.ListenAndServe()
}
# 第二步 关闭服务 根据端口 开启一个 tcp 监听
func (srv *Server) ListenAndServe() error {
	if srv.shuttingDown() {
		return ErrServerClosed
	}
	addr := srv.Addr
	if addr == "" {
		addr = ":http"
	}
	ln, err := net.Listen("tcp", addr)
	if err != nil {
		return err
	}
	return srv.Serve(tcpKeepAliveListener{ln.(*net.TCPListener)})
}
# 第三步 处理 请求
func (srv *Server) Serve(l net.Listener) error {
	if fn := testHookServerServe; fn != nil {
		fn(srv, l) // call hook with unwrapped listener
	}

	l = &onceCloseListener{Listener: l}
	defer l.Close()

	if err := srv.setupHTTP2_Serve(); err != nil {
		return err
	}

	if !srv.trackListener(&l, true) {
		return ErrServerClosed
	}
	defer srv.trackListener(&l, false)

	var tempDelay time.Duration     // how long to sleep on accept failure
	baseCtx := context.Background() // base is always background, per Issue 16220
	ctx := context.WithValue(baseCtx, ServerContextKey, srv)
	for {
		rw, e := l.Accept()
		if e != nil {
			select {
			case <-srv.getDoneChan():
				return ErrServerClosed
			default:
			}
			if ne, ok := e.(net.Error); ok && ne.Temporary() {
				if tempDelay == 0 {
					tempDelay = 5 * time.Millisecond
				} else {
					tempDelay *= 2
				}
				if max := 1 * time.Second; tempDelay > max {
					tempDelay = max
				}
				srv.logf("http: Accept error: %v; retrying in %v", e, tempDelay)
				time.Sleep(tempDelay)
				continue
			}
			return e
		}
		tempDelay = 0
		c := srv.newConn(rw)
		c.setState(c.rwc, StateNew) // before Serve can return
		go c.serve(ctx)
	}
}
```