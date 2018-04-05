<?php
class Websocket{
	public $server;
	public function __construct(){
		$this->server = new swoole_websocket_server('0.0.0.0',6060);
		//服务链接成功时 推送给链接的所有人有人上线了
		$this->server->on('open',function($server,$request){
			$server->push($request->fd,'欢迎来到我的swoole socket '.$request->fd.'号游客');
			$count = 1;
			foreach($this->server->connections as $fd){
                                if($fd != $request->fd && $server->exist($fd) ){
                                        $server->push($fd,$request->fd.'号游客已上线');
					++$count;	
                                }
                        }
			foreach($this->server->connections as $fd){
				if($server->exist($fd)){
					$server->push($fd,'目前总在线人数为:'.$count.'人');
				}
			}
		});
		//服务收到信息发送给所有人
		$this->server->on('message',function(swoole_websocket_server $server,$frame){
			//$server->push($frame->fd,$frame->data.' and we will push allconnecter');
			foreach($this->server->connections as $fd){
				if($server->exist($fd)){
					$server->push($fd,$frame->fd.' 号游客 :'.$frame->data);
				}			
			}
		});
		//服务关闭时通知所有人
		$this->server->on('close',function($ser,$fd){
			//给除自己之外的所有人推送下线消息
			//$temp = $this->server->connections[$fd];
			//var_dump($temp); ['websocket_status']的状态可以实现 是否为websocket判断
			$count = 0;
			foreach($this->server->connections as $fds){
				//echo $fds;
				if($fds != $fd && $this->server->exist($fds)){
					$this->server->push($fds,$fd.'号游客已经下线');
					++$count;
				}
			}
			foreach($this->server->connections as $fdss){
                                if($fd != $fdss && $this->server->exist($fdss)){
                                        $this->server->push($fdss,'目前总在线人数为:'.$count.'人');
                                }
                        }
		});
		//这里是系统发布消息的地方
		$this->server->on('request',function($request,$response){
			foreach($this->server->connections as $fd){
				if($this->server->exist($fd)){
					$this->server->push($fd,'系统通知:'.$request->get['message']);
				}
			}
		});
		$this->server->start();
	}
}
new Websocket();
