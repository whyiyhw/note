<?php
class ws{
    //服务器端的socket
    private $mainsocket;
    //socket 队列
    public $socketList = [];
    //链接事件
    public $onConnction;
    public $onMessage;
    public $onClose;

    public function __construct()
    {
        $this->mainsocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //进行服务器设定
        socket_set_option($this->mainsocket,SOL_SOCKET,SO_REUSEADDR,1);
        //绑定端口
        socket_bind($this->mainsocket, '0.0.0.0',6060);
        //监听
        socket_listen($this->mainsocket,5);
        //把服务器的socket存入socket队列
        $this->socketList[(int)$this->mainsocket] = $this->mainsocket ;

        echo 'server start...';
    }

    //运行
    public function run()
    {
        while (true){
            $read = $this->socketList;
            $write = $except = null;
            //删除不活跃的socket
            $status = socket_select($read,$write,$except,null);
            if(!$status) continue;

            foreach ($read as $socket){

                //第一次来访问
                if($this->mainsocket == $socket){
                    $clientSocket = socket_accept($socket);
                    //获取客户端头信息
                    $headDate = socket_read($clientSocket,8000);
                    //调用函数进行websocket链接
                    $this->handshaking($headDate,$clientSocket);
                    //拿到链接的一个ip和端口
                    socket_getpeername($clientSocket,$addr,$port);

                    //把客户端的一个socket 存入队列
                    $this->socketList[(int)$clientSocket] = $clientSocket;
                    $read[(int)$clientSocket] = $clientSocket;
                    //删除主socket 保证只执行一次
                    unset($read[(int)$this->mainsocket]);
                    //注册事件

                    if($this->onConnction){
                        call_user_func($this->onConnction,$clientSocket);
                    }
                }else{
                    //获取客户发送过来的数据
                    $buff = socket_read($socket,8000);

                    if($buff === ''|| $buff === false){
                        //删除队列socket
                        unset($read[(int)$socket]);
                        unset($this->socketList[(int)$socket]);
                        if($this->onClose){
                            call_user_func($this->onClose);
                        }
                        socket_close($socket);
                        continue;
                    }else{
                        if($this->onMessage){
                            call_user_func($this->onMessage,$socket,$buff);
                        }
                    }

                }//end first connect
            }//end foreach
        }//end while
    }//end run

    /**
     * @param $header //客户端头信息
     * @param $activeSocket //客户端的socket对象
     */
    function handshaking($header, $activeSocket)
    {
        preg_match('/Sec\-WebSocket\-Key: (.*)\r\n/', $header, $out);//拿到key
        $lock = base64_encode(sha1(($out[1] . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11"), true));//进行加密
        $head = "HTTP/1.1 101 Switching Protocols\r\n";
        $head .= "Upgrade: websocket\r\n"; // 告诉这个websocket客户端可以把这个协议升级为websocket协议
        $head .= "Connection: Upgrade\r\n"; // 升级这个通信协议
        $head .= "Sec-WebSocket-Accept: {$lock}\r\n\r\n"; // 最后的一定要有两个回车
        // 握手响应协议信息返回给浏览器
        socket_write($activeSocket, $head);
    }

}
$server = new ws();
//实现函数
//链接
$server->onConnction = function ($socket){

    $msg = '游客'.(int)$socket.'号你好! 欢迎来到我的聊天室.';
    socket_write($socket,enmask($msg));
};
//会话
$server->onMessage = function($socket,$msg){
    $msg = unmask($msg);
    //广播给所有人
    $int  = (int)$socket;
    broadcastAll($msg,$int);
};
$server->run();
// 加码数据

function enmask($str) {
    $a  = str_split($str, 125);
    $ns = "";
    foreach ($a as $o) {
        $ns .= "\x81" . chr(strlen($o)) . $o;
    }
    return $ns;
}
//解码
function unmask($text) {
    $length = ord($text[1]) & 127;
    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data  = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data  = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data  = substr($text, 6);
    }
    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }
    return $text;
}
function broadcastAll($msg,$int){
    global $server;
    foreach ($server->socketList as $socket){
        if($int == (int)$socket){
            $tmp = enmask('自己说:'.$msg);
        }else{
            $tmp = enmask($int.'号游客说:'.$msg);
        }
        @socket_write($socket,$tmp);
    }
}
?>