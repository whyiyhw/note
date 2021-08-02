## 核心概念
- 镜像(Image)
    - 一个docker引擎认识的模板
    - 镜像可以是一个操作系统,也可以只是一个软件
    - 镜像是构建容器的的基础
    - 可以通过版本控制和增量的文件系统来创建和更新镜像
- 容器(Container)
    -  是一个轻量级的沙箱,docker利用容器来运行和隔离应用.
    -  容器是从镜像创建的应用运行实例,可以将其启动,开始,停止,删除,这些容器都是相互隔离,互不可见.
    -  可以理解为简易的linux系统,将运行在linux中的应用程序打包为应用盒子
    -  镜像本身是只读的.容器从镜像启动的时候,Docker会在镜像的最上层创建一个可写层,镜像本身将保持不变
- 仓库(Repository)
    -  类似于git的代码仓库
    -  云端仓库 Docker Hub 可以将你本地仓库推送到云端,从而使各个地方的人都可见可用

## 镜像
- 获取镜像
    - 
    -  **==docker pull==** 用来获取云端的镜像
- 查看镜像
    - 
    - docker images
        - 属性
        - REPOSITORY   来自哪个仓库
        - TAG          标签
        - IMAGE ID     镜像ID(唯一)
        - CREATED      创建时间(拉取的时间)
        - SIZE         尺寸
    - 如果image id一致那么这两个镜像就属于同一个镜像只是别名不同而已
    - **==docker inspect==** (IMAGE ID) 查看详细信息
- 搜索镜像
    - 
    - **==docker search==**
        - 默认会到docker hub 官网上进行搜索
        - 属性
            - INDEX 来自
            - NAME  名字
            - DESCRIPTION 描述
            - STARS 受关注数
            - OFFICE 是否官方
            - AUTOMATED 是否自动创建
- 删除镜像
    - 
    - docker rmi (IMAGE ID)|(TAG)
    - ==当有该镜像创建的容器存在时,镜像文件默认是无法被删除的==
    - 可以使用 -f 参数 来强行删除镜像(不推荐/会产生临时镜像)
    - 一般都是先删除依赖该容器的所有镜像,再删除镜像
    - `docker rm (CONTAINER ID)`删除容器
- 创建镜像
    - 
    - 方式一 基于已有的镜像的容器创建
        - 主要使用docker commit
        - `docker commit [option] container [repository[:TAG]]`
        - `-a --author "" 作者信息`
        - `-m --message "" 提交信息`
        - `-p --pause=true 提交时暂停容器运行`
        ```
            docker run -ti 113a//创建/进入容器
            touch index.php 创建index.php
            exit
            docker ps -a //查看容器id
            docker commit -m "add a new file" -a "docker newer" cf2e6a8413e5 test
            //创建新的镜像 名字叫 test
        ```
    - 方式二 基于本地模板的导入
        - 可以使用`docker save` `docker load` 来导出 导入镜像
        - 导出 `docker save -o centos7.tar docker.io/centos`
        - 导入 `docker load < centos7.tar` 
    - 方式三 基于Dockerfile创建
        - 这个后续单独写
- 上传镜像
    - 
    - docker push NAME[:TAG] 
    ```
    //添加新标签 然后上传
    docker tag test:latest xueyi/test:latest//更改标签名
    docker push xueyi/test:latest//推到远程
    //会提示你登录 输入用户信息
    ```
## 容器
#### 容器并不是一个完整的虚拟机 它是一个或一组应用运行的环境
- 查看所有存在的容器
    - 
    - **==docker ps -a==**
    - 属性 
        - CONTAINER ID  容器ID
        - IMAGE         镜像名
        - COMMAND       执行的命令
        - CREATED       创建的时间
        - STATUS        状态(up|exit 时间)
        - PORTS         使用的端口号
        - NAMES         容器名
- 创建容器(未启动)
    - 
    - `docker create` 命令来创建容器
        -  会返回 容器的id 此时容器是处于停止状态
        -  可以使用 docker start 来启动
- 新建并启动容器  
    -
    - `docker run === (docker create && docker start)`
    - `docker run -t -i docker.io/centos /bin/bash` 
    - -t 让docker 分配一个伪终端并绑定到容器输入上
    - -i 让容器的标准输入保持打开
    - /bin/bash 监听输入并与其交互
    - 在exit 后 该容器就处于 终止状态
- 守护态运行
    - 
    - 更多时候我们需要容器一直在后台运行
    - 使用参数 **==-d==**
    - daemonized
    - 容器的输出  可以使用
    - docker logs [container id] 来打印对应容器的输出
- 终止容器
    - 
    - docker stop [container id] (终止一个运行中的容器) 
    - 它不会马上结束 首先会向容器发送SIGTERM信号,等待(默认是10秒),在发送SIGKILL信号终止容器
    - `docker kill`是直接发送SIGKILL信号来强行终止容器
    - 处于终止状态的容器 可以使用 `docker start` 来重新启动
    - `docker restart` 可以将一个容器重启
- 进入容器
    - 
    - docker attach 命令
        - `docker attach [container ID]` 可以到容器内
    - exec工具
        - `docker exec -ti [container ID] /bin/bash`
- 删除容器
    -
    - docker rm [container ID]
    - -f ,--force=false 强行终止并删除一个运行中的容器
    - -l ,--link=false 删除容器链接,但保留容器
    - -v ,--volumes=false 删除容器挂载的数据卷
- 导入和导出容器
    - 
    - 导出 `docker export [container id] > test.tar`
    - 导入 导出的文件可以使用docker import 导入为镜像
    ```
    cat test.tar | docker import name.tar test/contod:v1.0
    ```
    - 与 docker load 的区别 就是 load 不可改
    但是 import 可以指定新的信息 import 导入的仅仅是快照的状态 比较小
    
## 数据管理
- 数据卷
    - 
    - 数据卷是一个可供容器使用的特殊目录
        - 数据卷可以在容器之间共享使用
        - 对数据卷的修改马上就会生效
        - 对数据卷的更新不会影响镜像
        - 卷会一直存在,直到没有容器使用
        - 类似于linux的mount(挂载) 将一个真实的目录挂载到容器内的目录下
    - 在容器创建时创建数据卷
        - docker run -v 
        - -v 命令可以创建 一个数据卷
        - 多次使用 -v 可以创建多个数据卷
        - `docker run -d -P --name web -v /var/www /var/www php index.php`
        - -P 是容器需要暴露的端口
        - -v 也可以挂载一个本地主机文件作为数据卷
        - 可以把nginx 的配置文件挂载给容器...
        - 主要就是挂载的文件是比较稳定
- 数据卷容器
    - 
    - 如果用户需要在容器之间共享一些持续更新的数据
        - 最简单的方式 就是使用数据卷容器
        - 数据卷容器就是一个普通的容器
    - 创建数据卷容器 dbdata 并在其中 创建一个数据卷挂载到/dbdata
    - `docker run -it -v /dbdata --name dbdata docker.io/contos`
    - 这样就在dbdata 容器里面创建了数据卷 /dbdata
    - `docker run -it --volumes-from dbdata --name db1 docker.io/centos`
    - `docker run -it --volumes-from dbdata --name db2 docker.io/centos`
    - 进入 `docker attach [container id]`
    - `cd /dbdata && touch index.php`
    - `exit `
    - 再进入到 dbdata容器 中 发现index.php 已经被共享成功了0.0
    
    - 可以多次使用 --voulumes-from [container name] 可以从多个容器挂载多个数据卷 还可以从已经挂载了容器卷的容器挂载数据卷
    - 使用 --voulumes-from 的容器本身并不需要保持运行状态
    
    - 如果删除了挂载的容器（包括dbdata,db1,db2）数据卷并不会被自动删除。如果要删除一个数据卷，必须要在`最后一个还挂载它的容器时显式的使用 docker rm -v` 来指定同时 删除关联容器  
- 利用容器进行数据迁移
    -
    - 备份
        - 
        - `docker run --volumes-from dbdata -v $(pwd):/backup --name worker docker.io/centos tar cvf /backup/backup.tar /dbdata`
        - 首先是创建了一个contos容器 worker 挂载了dbdata 数据卷
        - 使用 -v $(pwd):/backup 参数 挂载本地的当前目录到 worker 容器的 /backup目录
        - worker 启动后 使用tar cvf /backup/backup.tar /dbdata 命令来将/dbdata 下的内容 备份到 容器内的/backup/backup.tar - 也就是宿主主机当前目录下的backup.tar
    - 恢复
        - 
        - 如果要恢复数据到一个容器
        - `docker run -v /dbdata --name dbdata2 docker.io/centos /bin/bash`
        - 然后创建另一个新的容器，挂载dbdata2的容器，并使用untar解压备份文件到所挂载的容器卷中即可
        - `docker run --volumes-from dbdata2 -v $(pwd):/backup busybox tar xvf /backup/backup.tar`
 
## 网络基础配置
- 端口映射实现访问容器
    - 
    - docker run  添加 -p/-P参数 来指定端口映射
    - 当使用-P 时 Docker 会随机映射一个49000~49900的端口至容器内部开放的网络端口
    - `-p`则可以指定要映射的端口，并且，在一个指定端口上只可以绑定一个容器 支持的格式有
    1. IP：hostPost:containerPort
    2. ip::containerPort
    3. hostPort:containerPort
    - 多次使用-p可以绑定多个端口映射到内部
    - `docker run -d -p 5000:5000 -p 3306:3306 xueyi/lnmp php index.php`
    - 上面分为三部分 docker run 创建容器并启动
        - -d -p 设定启动的参数 
        - xueyi/lnmp 从哪个镜像创建 
        - php index.php 是指启动都使用php 去执行index.php文件
    - -d 是守护进程
    - 加上 --name 可以指定容器的名字 如 --name web
- 容器互联实现容器间的通信
    - 
    - 1. 自定义容器名
        - --name web --name db 这样就很好区分了
        - `docker run -d  -p 80:80 --name web xueyi/nginx `
        - `docker run -d  -p 3306:3306 --name db xueyi/mysql`
        - 名字是唯一的 如果起了冲突 必须rm 一个
        - docker run --rm  
        - --rm 意思是在容器终止后立即删除 会和 -d 守护进程冲突 二选一
    - 2. 容器互联
        - 先创建一个数据库容器
        - `docker run -d -p --name mysql xueyi/mysql`
        - 然后创建一个新的web容器 并将它链接到db容器
        - `docker run -d -P --name web --link db:db xueyi/nginx` 
        - 这时候 db容器和web容器建立了互联关系
        - --link 参数的格式是 --link name:alias name是要链接容器的名字 alias是链接的别名
        - 此时db容器的name 为 db,web/db 这表示web容器链接到db容器 这允许web容器 访问db容器的信息
        - docker在两个互联的容器之间建立了一个安全隧道，而且不用映射它们的端口给宿主主机上.
- Docker 通过两种方式为容器公开连接信息
    - 
    - 1.  环境变量
        - 使用env 命令来查看容器的环境变量
        - `docker run --rm --name web2 --link db:db xueyi/nginx env`
        - 
        ```
        DB_NAME=/web2/db
        DB_PORT=tcp://172.17.0.5:5432
        DB_PORT_5000_TCP=tcp://172.17.0.5:5432
        DB_PORT_5000_TCP_PROTO=tcp
        DB_PORT_5000_TCP_PORT=5432
        DB_PORT_5000_TCP_ADDR=172.17.0.5
        ```
        很显然其中DB_开头的环境变量是供web容器链接db容器使用的，前缀采用大写的连接别名
    - 2. 父类的 /etc/hosts 文件
        - Docker还添加了链接信息给父容器的/etc/hosts 的文件 下面是web的hosts 文件
        - cat /etc/hosts
        ```
        172.17.0.7 aed84ee21dbe
        ...
        172.17.0.5 db
        ```
        这里的两个hosts信息 一个是web容器 以container id 为别名
        另一个就是链接的DB容器的ip 和主机名
        - 可以试着 ping db   会被解析成 ping 172.17.0.5
- 使用dockerfile 来创建容器
    - 
    - Dockerfile 是一个文本格式的配置文件，用户可以使用Dockerfile快速创建自定义镜像
    - 基本操作
        -  Dockerfile 支持以#开头的注释行
        -  一般分为4个部分
            - 基础镜像部分
            - 维护者信息
            - 镜像操作指令
            - 容器启动时执行指令
        - 如：
        ```
        # This dockerfile use the ubuntu image
        # VERSION 2
        # Author: docker_user
        # Command format:Instruction
        # 第一行必须指定基于基础镜像
        FROM centos
        
        # 维护者信息
        MAINTAINER docker_user docker_user@email.com
        
        # 镜像的操作指令
        RUN yum update && yum install -y nginx
        RUN echo "ndaemon off;" >> /etc/nginx/nginx.conf
        
        # 容器启动时执行指令
        CMD /use/sbin/nginx
        ```
    - 指令
        - 
        - FROM
            - FROM的格式为FROM<image>:<tag>
        - MAINTAINER
            - 格式为 MAINTAINER<name>指定维护者信息
        - RUN
            - RUN <command> or RUN ['executable','parm1','parm2']
            - 前者将在shell终端中执行命令 即/bin/sh -c
            - 后者则使用exec执行。 ["/bin/bash","-c","echo hello"]
            - 每条RUN指令将在当前镜像上执行指定命令，并提交为新的镜像 可以使用 \来换行
        - CMD
            - 支持三种格式
                - CMD['executable','parm1','parm2']使用exec执行
                - CMD command param1 param2 由/bin/sh 执行
                - CMD ['parm1','parm2'] 提供 entrypoint的默认参数
                - 每个dockerfile 只能有一条CMD命令，如果指定了多条命令，那么只有最后一条会被执行
                - 如果 用户执行的使用指定了命令 那么cmd的命令将会被覆盖
        - EXPOSE
            - 格式为 EXPOSE <port>...
            - EXPOSE 22 80 8443
            - 告诉Docker 服务端容器暴露的端口号，可以在docker run -p 来指定对应端口号映射
        - ENV
            格式为 ENV <KEY><VALUE>指定一个环境变量会被后续的RUN指定使用 并在容器运行时保持
        - ADD <SRC><DEST>
            - 复制指定的src 到容器的 dest 
            - 可以是dockerfile的相对路径/URL/tar文件
        - COPY
            - 复制本地主机的src 到 dest
        - ENTRYPOINT ['executable','parm1','parm2']
            - 配置容器时指定的命令
        - VOLUME ["/data"]
            - 指定挂载的数据卷
        - USER 
            - USER daemon
        - WORKDIR
            - WORKDIR /path/to/workdir
            - 为后续的命令指定路径
            - 可多次使用
        - ONBUILD
            - ONBUILD [INSTRUCTION]
            - 配置当前所创建的镜像作为其它新创建镜像的基础镜像时，所执行的命令
- 创建镜像
    - 使用docker build 来创建
    - 如要确定镜像的标签属性 可以通过-t 选项
    - docker build -t build_repo/first_image /tmp/docker_builder/
    - 指定/tmp/docker_builder/ 来创建 tag 为 build_repo/first_image 的镜像