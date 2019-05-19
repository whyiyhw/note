- 没有网线的情况下 virtualBox 用的很难受
    - virtualBox 实现内外网互通
        - 最主要的就是理解网卡模式
        - NAT 只能访问外网，不能被主机本身访问
        - Host-Only 只能被局域网访问 不能访问外网
        - 内外互通实现就是开两张网卡连接的是host-only 连接外网使用另一张 还需要 （端口转发？不确定）
## 起步 安装
- `yum update`
- 我使用的是contOS7的源所以不用更换源
- `yum intsall -y docker && systemctl enable docker`
- 安装并开机自启docker
    
## 安装并使用mysql镜像
- `docker pull mysql:5.6`
- 不用5.7/8.0的原因是不用设定远程账户~
- 建议5.7以上  但实际上代码不写的太烂用不到 5.7以上/反正也没人用
 
    ```
     docker run -d -p 3307:3306\
     --name mysql56 -e MYSQL_ROOT_PASSWORD=123456\
     mysql:5.6
    ```
    - -d 守护进程 -p端口转发 -e 设定初始化密码
    - --name 设定容器 名
- 进行连接测试 成功 注意端口为之前设定的3307
## 安装并使用mysql镜像
- `docker pull  redis:3.2`
- `docker run -d -p 6379:6379  --name redis32  redis:3.2 redis-server`

## 安装并使用php-fpm镜像
- `docker pull php:7.1-fpm`
    - 
    - 阿里云似乎今天炸了半天, 据说又是实习生背锅，我反正是不信的，越来越贵的阿里云...
    - 忘记更换镜像了
    - `vim /etc/docker/daemon.json`
    - 
```
 "registry-mirrors": ["https://xxxxx.mirror.aliyuncs.com"]
```
    - 有阿里云就能使用上面的镜像
    - `https://cr.console.aliyun.com/#/accelerator`
    - 上面是网址可以拿到自己的镜像加速
    - `systemctl daemon-reload`
    - `systemctl restart docker`
    
    - 重启了记得开mysql `docker start mysql56`
    - 
    ```
    docker run -d \
    -v /var/www/laravel:/var/www/html \
    -p 9000:9000 --link mysql56:mysql\
    --name phpfpm71  php:7.1-fpm
    ```
    - 运行生成一个php7.1的容器
    - `docker exec -ti phpfpm71 /bin/bash`
    - -ti 打开图形界面
    - /bin/bash 执行 这个命令
    - exec  可以在 不exit的情况下进行附加操作
    - 进去后需要 `apt-get update` 里面是debian系统
    - `docker-php-ext-install pdo_mysql` 来装pdo_mysql扩展
    - `apt-get install net-tools` 来装网络工具包
    - 使用 netstat -tunlp 来查看 端口很网络状态可以看到9000端口正在被监听
## 安装nginx
    - `docker pull nginx` 
    - 
    ```
      docker run -d -v /var/www/laravel:/var/www/html -p 80:80 --name nginx --link phpfpm71:phpfpm --privileged=true nginx:latest
      ```
    - `docker exec -ti nginx /bin/bash`
    - `apt-get update`
    - `apt-get install net-tools`
    - `apt-get install -y vim`
    - `vim /etc/nginx/conf.d/default.conf `
```
    server {
        listen       80;
        server_name  _;
        root   "/var/www/html";
        location / {
            index  index.html index.htm index.php;
            #autoindex  on;
        }
        location ~ \.php(.*)$ {
            fastcgi_pass   phpfpm71:9000;
            fastcgi_index  index.php;
            fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO  $fastcgi_path_info;
            fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
            include        fastcgi_params;
        }
    }
```
    - 如果 以上操作是在云端服务器上完成的 那么 你可以在容器中exit 后去 /var/www/laravel vim 一个index.php来查看效果
    - 但是我是在本地虚拟机上完成的 由于权限问题 所以 需要删除 再来
    - 就是在 docker run  这步 加上 `--privileged=true`
    - `nginx -s reload` 就搭建好了一个dnmp环境... 
    
    apt-get install libmcrypt-dev
    
    来装mcrypt
    
    ```
    mv /etc/apt/sources.list /etc/apt/sources.list.bak && \
    echo "deb http://mirrors.163.com/debian/ jessie main non-free contrib" >/etc/apt/sources.list && \
    echo "deb http://mirrors.163.com/debian/ jessie-proposed-updates main non-free contrib" >>/etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ jessie main non-free contrib" >>/etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ jessie-proposed-updates main non-free contrib" >>/etc/apt/sources.list
    ```
    更新apt-get源
    
    
    ## 重新从镜像 转成容器的时候  为了避免 直接退出需要 加上 docker-php-entrypoint php-fpm  这个命令
    ```
    docker run -d -v /var/www:/var/www/html -p 9000:9000 --link mysql56:mysql --name phpfpm-71 615e docker-php-entrypoint php-fpm
    ```
    
    容器和镜像文件之间的导入导出
    
    - 导出 docker export [container id] > test.tar
    
    - 导入 导出的文件可以使用docker import 导入为镜像
        - `docker import - test/contod:v1.0`
    