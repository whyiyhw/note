<?php
//创建一http server

$http = new swoole_http_server("0.0.0.0",9501 );

$http->set([ 'worker_num' => 5,'daemonize' =>1 ]);

$http->on('request', function ($request, $response) {
	//var_dump($request);
	//var_dump($response);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$http->start();
