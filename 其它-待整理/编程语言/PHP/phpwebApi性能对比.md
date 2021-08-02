# 性能对比与统计

## 原生 `php`

```php
<?php
echo 'Hello!'.mt_rand(1,9999);
```

```shell
$ ./ab.exe -c 100 -n 10000 -k http://192.168.73.128:8000/
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 1000 requests
Completed 2000 requests
Completed 3000 requests
Completed 4000 requests
Completed 5000 requests
Completed 6000 requests
Completed 7000 requests
Completed 8000 requests
Completed 9000 requests
Completed 10000 requests


Server Software:        nginx/1.16.0
Server Hostname:        192.168.73.128
Server Port:            8000

Document Path:          /
Document Length:        10 bytes

Concurrency Level:      100
Time taken for tests:   6.185 seconds
Complete requests:      10000
Failed requests:        974
   (Connect: 0, Receive: 0, Length: 974, Exceptions: 0)
Keep-Alive requests:    0
Total transferred:      1728917 bytes
HTML transferred:       98917 bytes
Requests per second:    1616.70 [#/sec] (mean)
Time per request:       61.854 [ms] (mean)
Time per request:       0.619 [ms] (mean, across all concurrent requests)
Transfer rate:          272.96 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      1      12
Processing:    36   61   7.6     60      95
Waiting:       14   57   8.9     58      94
Total:         36   61   7.7     60      95
ERROR: The median and mean for the initial connection time are more than twice the standard
       deviation apart. These results are NOT reliable.

Percentage of the requests served within a certain time (ms)
  50%     60
  66%     63
  75%     65
  80%     67
  90%     72
  95%     75
  98%     79
  99%     81
 100%     95 (longest request)
Finished 10000 requests

```

## 使用 phalcon

```php
<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        echo 'Hello!'.mt_rand(1,9999);
    }
}
```

```shell
$ ./ab.exe -c 100 -n 10000 -k http://192.168.73.128:8000/
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 1000 requests
Completed 2000 requests
Completed 3000 requests
Completed 4000 requests
Completed 5000 requests
Completed 6000 requests
Completed 7000 requests
Completed 8000 requests
Completed 9000 requests
Completed 10000 requests


Server Software:        nginx/1.16.0
Server Hostname:        192.168.73.128
Server Port:            8000

Document Path:          /
Document Length:        10 bytes

Concurrency Level:      100
Time taken for tests:   6.712 seconds
Complete requests:      10000
Failed requests:        1052
   (Connect: 0, Receive: 0, Length: 1052, Exceptions: 0)
Keep-Alive requests:    0
Total transferred:      1728836 bytes
HTML transferred:       98836 bytes
Requests per second:    1489.93 [#/sec] (mean)
Time per request:       67.117 [ms] (mean)
Time per request:       0.671 [ms] (mean, across all concurrent requests)
Transfer rate:          251.55 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.5      1       4
Processing:    42   66   8.3     66     180
Waiting:       11   63   9.6     64     177
Total:         42   67   8.4     66     180
WARNING: The median and mean for the initial connection time are not within a normal deviation
        These results are probably not that reliable.

Percentage of the requests served within a certain time (ms)
  50%     66
  66%     69
  75%     71
  80%     72
  90%     76
  95%     80
  98%     88
  99%     92
 100%    180 (longest request)
Finished 10000 requests

```

## 使用 swoole 扩展的 swoft

```php
    /**
     * @RequestMapping("/testRand")
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function testRand(): Response
    {
        $return = 'Hello '.mt_rand(0,9999);
        return Context::mustGet()
            ->getResponse()
            ->withContent($return);
    }
```

```shell
$ ./ab.exe -c 100 -n 30000 -k http://192.168.73.128:18306/testRand
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 3000 requests
Completed 6000 requests
Completed 9000 requests
Completed 12000 requests
Completed 15000 requests
Completed 18000 requests
Completed 21000 requests
Completed 24000 requests
Completed 27000 requests
Completed 30000 requests


Server Software:        swoole-http-server
Server Hostname:        192.168.73.128
Server Port:            18306

Document Path:          /testRand
Document Length:        10 bytes

Concurrency Level:      100
Time taken for tests:   3.558 seconds
Complete requests:      30000
Failed requests:        2790
   (Connect: 0, Receive: 0, Length: 2790, Exceptions: 0)
Keep-Alive requests:    30000
Total transferred:      5514173 bytes
HTML transferred:       296963 bytes
Requests per second:    8431.76 [#/sec] (mean)
Time per request:       11.860 [ms] (mean)
Time per request:       0.119 [ms] (mean, across all concurrent requests)
Transfer rate:          1513.48 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       1
Processing:     1   12   5.6     11      48
Waiting:        1   12   5.6     11      48
Total:          1   12   5.6     11      48

Percentage of the requests served within a certain time (ms)
  50%     11
  66%     13
  75%     14
  80%     15
  90%     19
  95%     22
  98%     27
  99%     31
 100%     48 (longest request)
Finished 30000 requests

```

### swoft 连接远程数据库进行查询操作

```php
    /**
     * @RequestMapping(route="find")
     * @return array
     *
     * @throws Throwable
     */
    public function find(): array
    {
        $user = User::find(5);

        return $user->toArray();
    }
```

```shell
./ab.exe -c 24 -n 2000 -k http://192.168.73.128:18306/dbModel/find
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 200 requests
Completed 400 requests
Completed 600 requests
Completed 800 requests
Completed 1000 requests
Completed 1200 requests
Completed 1400 requests
Completed 1600 requests
Completed 1800 requests
Completed 2000 requests


Server Software:        swoole-http-server
Server Hostname:        192.168.73.128
Server Port:            18306

Document Path:          /dbModel/find
Document Length:        36 bytes

Concurrency Level:      24
Time taken for tests:   5.576 seconds
Complete requests:      2000
Failed requests:        0
Keep-Alive requests:    2000
Total transferred:      420000 bytes
HTML transferred:       72000 bytes
Requests per second:    358.66 [#/sec] (mean)
Time per request:       66.917 [ms] (mean)
Time per request:       2.788 [ms] (mean, across all concurrent requests)
Transfer rate:          73.55 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   2.2      0      99
Processing:    13   65  40.8     56     673
Waiting:       13   65  40.8     56     673
Total:         13   65  40.9     56     673

Percentage of the requests served within a certain time (ms)
  50%     56
  66%     59
  75%     62
  80%     62
  90%     65
  95%     67
  98%    259
  99%    260
 100%    673 (longest request)
Finished 2000 requests

```

## tp6.0 rc2

```php
Route::get('/', function (){
    return "hello".mt_rand(1,9999);
});
```

```shell
$ ./ab.exe -c 10 -n 1000 -k http://192.168.73.128:8001/
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests


Server Software:        nginx/1.16.0
Server Hostname:        192.168.73.128
Server Port:            8001

Document Path:          /
Document Length:        9 bytes

Concurrency Level:      10
Time taken for tests:   8.556 seconds
Complete requests:      1000
Failed requests:        0
Keep-Alive requests:    0
Total transferred:      299000 bytes
HTML transferred:       9000 bytes
Requests per second:    116.88 [#/sec] (mean)
Time per request:       85.559 [ms] (mean)
Time per request:       8.556 [ms] (mean, across all concurrent requests)
Transfer rate:          34.13 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      1       3
Processing:    15   85  43.0     78     269
Waiting:       15   85  43.0     78     269
Total:         15   85  43.0     79     269
ERROR: The median and mean for the initial connection time are more than twice the standard
       deviation apart. These results are NOT reliable.

Percentage of the requests served within a certain time (ms)
  50%     79
  66%     98
  75%    109
  80%    117
  90%    142
  95%    165
  98%    199
  99%    223
 100%    269 (longest request)
Finished 1000 requests

```

### 关于tp6

- 虽然性能上比较一般，但是写法风格较于 5.1|5.0 舒服了很多

## lumen5.8.8

```php
$router->get('/', function (){
    return "hello".mt_rand();
});

```

```shell
$ ./ab.exe -c 10 -n 1000 -k http://192.168.73.128:8002/
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests


Server Software:        nginx/1.16.0
Server Hostname:        192.168.73.128
Server Port:            8002

Document Path:          /
Document Length:        15 bytes

Concurrency Level:      10
Time taken for tests:   4.622 seconds
Complete requests:      1000
Failed requests:        483
   (Connect: 0, Receive: 0, Length: 483, Exceptions: 0)
Keep-Alive requests:    0
Total transferred:      249460 bytes
HTML transferred:       14460 bytes
Requests per second:    216.38 [#/sec] (mean)
Time per request:       46.216 [ms] (mean)
Time per request:       4.622 [ms] (mean, across all concurrent requests)
Transfer rate:          52.71 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      0       3
Processing:     9   46  34.8     35     258
Waiting:        8   45  34.8     35     257
Total:          9   46  34.8     36     258

Percentage of the requests served within a certain time (ms)
  50%     36
  66%     48
  75%     59
  80%     66
  90%     91
  95%    117
  98%    154
  99%    173
 100%    258 (longest request)
Finished 1000 requests
```

## golang Gin 框架

```go
package main

import (
    "github.com/gin-gonic/gin"
    "strconv"
    "time"
)

func main() {
    r := gin.Default()
    r.GET("/ping", func(c *gin.Context) {
        str := "hello"+ strconv.FormatInt(time.Now().UnixNano(), 10)
        c.JSON(200, gin.H{
           "message": str,
        })
    })
    r.Run() // listen and serve on 0.0.0.0:8080
}
```

```shell
$ ./ab.exe -c 100 -n 100000 -k http://192.168.73.128:8080/ping
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 10000 requests
Completed 20000 requests
Completed 30000 requests
Completed 40000 requests
Completed 50000 requests
Completed 60000 requests
Completed 70000 requests
Completed 80000 requests
Completed 90000 requests
Completed 100000 requests


Server Software:
Server Hostname:        192.168.73.128
Server Port:            8080

Document Path:          /ping
Document Length:        39 bytes

Concurrency Level:      100
Time taken for tests:   6.600 seconds
Complete requests:      100000
Failed requests:        0
Keep-Alive requests:    100000
Total transferred:      18600000 bytes
HTML transferred:       3900000 bytes
Requests per second:    15150.74 [#/sec] (mean)
Time per request:       6.600 [ms] (mean)
Time per request:       0.066 [ms] (mean, across all concurrent requests)
Transfer rate:          2751.99 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       8
Processing:     0    7   3.7      6      85
Waiting:        0    7   3.7      6      85
Total:          0    7   3.7      6      86

Percentage of the requests served within a certain time (ms)
  50%      6
  66%      7
  75%      8
  80%      9
  90%     11
  95%     12
  98%     15
  99%     17
 100%     86 (longest request)
Finished 100000 requests

```

### 进行数据库查询

```go

```

```shell

```

### beego

```go
func (u *UserController) Test() {
    str := "hello"+ strconv.FormatInt(time.Now().UnixNano(), 10)
    u.Data["json"] = str
    u.ServeJSON()
}
```

```shell
$ ./ab.exe -c 100 -n 50000 -k http://192.168.73.128:8080/v1/user/test
This is ApacheBench, Version 2.3 <$Revision: 1748469 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.73.128 (be patient)
Completed 5000 requests
Completed 10000 requests
Completed 15000 requests
Completed 20000 requests
Completed 25000 requests
Completed 30000 requests
Completed 35000 requests
Completed 40000 requests
Completed 45000 requests
Completed 50000 requests


Server Software:        beegoServer:1.11.2
Server Hostname:        192.168.73.128
Server Port:            8080

Document Path:          /v1/user/test
Document Length:        26 bytes

Concurrency Level:      100
Time taken for tests:   3.860 seconds
Complete requests:      50000
Failed requests:        0
Keep-Alive requests:    50000
Total transferred:      10050000 bytes
HTML transferred:       1300000 bytes
Requests per second:    12953.22 [#/sec] (mean)
Time per request:       7.720 [ms] (mean)
Time per request:       0.077 [ms] (mean, across all concurrent requests)
Transfer rate:          2542.57 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       1
Processing:     0    8   2.7      7      76
Waiting:        0    8   2.6      7      65
Total:          0    8   2.7      7      77

Percentage of the requests served within a certain time (ms)
  50%      7
  66%      8
  75%      9
  80%      9
  90%     10
  95%     11
  98%     12
  99%     14
 100%     77 (longest request)
Finished 50000 requests
```

## 数据库操作

http://authserver.hznu.edu.cn/authserver/login?service=http://ehall.hznu.edu.cn/login?service=http://ehall.hznu.edu.cn/new/index.html

这个后台服务是 java 做的
http://ehall.hznu.edu.cn
172.31.211.85


http://hys.hznu.edu.cn/webhsd/start
172.31.223.25