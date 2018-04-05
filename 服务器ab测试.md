### 单用户请求十次
```
E:\phpStudy\Apache\bin>ab.exe -n 10 -c 1 -k http://127.0.0.1/index.php

Server Software:        nginx/1.12.2      #服务器
Server Hostname:        127.0.0.1　　　	  #请求的主机名IP
Server Port:            80				  #请求端口

Document Path:          /index.php  	  #请求的路径
Document Length:        36975 bytes		  #请求的文件大小

Concurrency Level:      1				  #并发量
Time taken for tests:   18.464 seconds    #总时间
Complete requests:      10				  #请求的总次数
Failed requests:        0				  #失败的次数  一旦大于0就说明达到了上限
Keep-Alive requests:    0				  
Total transferred:      372700 bytes	  #总共传输的的字节 包含相应头
HTML transferred:       369750 bytes	  #总共传输的的字节 不包含相应头
Requests per second:    0.54 [#/sec] (mean)  #每秒处理多少请求，服务器的吞吐量[重要] QPS
Time per request:       1846.413 [ms] (mean) #用户的平均请求等待时间
Time per request:       1846.413 [ms] (mean, across all concurrent requests)
Transfer rate:          19.71 [Kbytes/sec] received  #每秒获取的数据长度

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.5      0       1
Processing:  1188 1846 245.7   1908    2059
Waiting:      736 1356 222.5   1403    1500
Total:       1188 1846 245.8   1908    2059

Percentage of the requests served within a certain time (ms)
  50%   1908 ## 50%的请求在1908ms内返回 
  66%   1914 ## 66%的请求在1914ms内返回 
  75%   1956
  80%   2042
  90%   2059
  95%   2059
  98%   2059
  99%   2059
 100%   2059 (longest request) ## 最长时间的请求为 2059毫秒
 ```
 ### 并发10
 ```
 E:\phpStudy\Apache\bin>ab.exe -n 10 -c 10 -k http://127.0.0.1/index.php

Server Software:        nginx/1.12.2
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /index.php
Document Length:        36975 bytes

Concurrency Level:      10
Time taken for tests:   18.891 seconds
Complete requests:      10
Failed requests:        8
   (Connect: 0, Receive: 0, Length: 8, Exceptions: 0)
Keep-Alive requests:    0
Total transferred:      372728 bytes
HTML transferred:       369778 bytes
Requests per second:    0.53 [#/sec] (mean)
Time per request:       18890.664 [ms] (mean)
Time per request:       1889.066 [ms] (mean, across all concurrent requests)
Transfer rate:          19.27 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      0       1
Processing:  7375 14786 3766.5  16372   18889
Waiting:     6838 13628 3799.7  14865   18640
Total:       7375 14787 3766.5  16372   18889

Percentage of the requests served within a certain time (ms)
  50%  16372
  66%  16447
  75%  17377
  80%  18761
  90%  18889
  95%  18889
  98%  18889
  99%  18889
 100%  18889 (longest request)
```

### １核１Ｇ最大压力 5并发
```
 E:\phpStudy\Apache\bin>ab.exe -n 7 -c 5 -k http://127.0.0.1/index.php
 
Server Software:        nginx/1.12.2
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /index.php
Document Length:        36975 bytes

Concurrency Level:      5
Time taken for tests:   12.938 seconds
Complete requests:      7
Failed requests:        0
Keep-Alive requests:    0
Total transferred:      260890 bytes
HTML transferred:       258825 bytes
Requests per second:    0.54 [#/sec] (mean)
Time per request:       9241.286 [ms] (mean)
Time per request:       1848.257 [ms] (mean, across all concurrent requests)
Transfer rate:          19.69 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.5      0       1
Processing:  3927 7602 2268.8   8626    9568
Waiting:     3706 6717 1840.4   7359    8518
Total:       3928 7603 2268.5   8626    9568

Percentage of the requests served within a certain time (ms)
  50%   7837
  66%   9414
  75%   9567
  80%   9567
  90%   9568
  95%   9568
  98%   9568
  99%   9568
 100%   9568 (longest request)
 ```