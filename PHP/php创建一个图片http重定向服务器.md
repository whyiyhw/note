```
<?php
$_SERVER = array();
//创建一个tcp套接字,并监听9898端口
if($web = stream_socket_server('0.0.0.0:6060',$errno,$errstr)){
    while(true){
        $conn = @stream_socket_accept($web);
        if($conn){
			$host1 = 'https://admin.rujcar.com';
			$host2 = 'https://api.rujcar.com';
			//$host3 = 'http://localhost:8080';
            $_SERVER = array();
            decode(fgets($conn));

			$query = $_SERVER['REQUEST_URI'];

			foreach([$host1,$host2] as $v)
			{
				if (url_is_exist($v.$query)){
					fwrite($conn,encodes(url_is_exist($v.$query)));
				}
			}

			fclose($conn);
        }
    }
}else{
    die($errstr);
}
//http协议解码
function decode($info){
    global $_SERVER;
    list($header,) = explode("\r\n\r\n",$info);
    //将请求头变为数组
    $header = explode("\r\n",$header);
    list($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_PROTOCOL']) = explode(' ', $header[0]);
    //$_SERVER['QUERY_STRING'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    //$_SERVER['QUERY_STRING'] = $_SERVER['REQUEST_URI'];

}
//http协议加密
function encode($str){
    $content = "HTTP/1.1 200 OK\r\nContent-Type: text/html; charset=utf-8\r\nServer: simple_web/1.0.0\r\nContent-Length: " . strlen($str   )."\r\n\r\n{$str}";
    return $content;
}

//http图片协议加密
function encodes($str){
    $content = "HTTP/1.1 200 OK\r\nAccept-Ranges: bytes\r\nContent-Type: image/jpeg\r\nServer: simple_web/1.0.0\r\nContent-Length: " . strlen($str   )."\r\n\r\n{$str}";
    return $content;
}

function url_is_exist($url){

	$res = file_get_contents($url);
	$fileExists = $res ? true : false;
	if(!$fileExists){
		return $fileExists;
	} else {
		return $res;
	}
}
```