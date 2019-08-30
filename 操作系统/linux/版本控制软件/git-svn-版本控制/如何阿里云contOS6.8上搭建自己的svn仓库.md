1.安装svn
```
yum -y install subversion
```
2.配置

建立版本库目录
```
mkdir /var/svn

svnserve -d -r /var/svn
```
3.建立版本库

创建一个新的Subversion项目
```
svnadmin create /var/svn/project
```
4.配置允许用户jiqing访问
```
cd /var/svn/project
//你能看到以下目录
conf  db  format  hooks  locks  README.txt
```
5.修改conf目录下的文件允许用户进行访问
```
cd /conf
#可以看到以下文件
authz  passwd  svnserve.conf
#分别对应用户 密码 和 svn配置文件
vi authz
#在最后面紧贴着头（不留空格）加上 意思是给user组 读写的权限
[/]
user=rw

vi passwd 
user = xxx
#就是你的用户名  给多人配可以分群组，我是自己用就不介绍了

vi svnserve.conf
#将这些行前的#去掉 且前面不留空格
anon-access = read
auth-access = write
password-db = passwd
authz-db = authz
#并在最后加上 你的项目文件所在目录就完成了
realm = /var/svn/project
```
5.客户端连接
```
svn://你的IP
```
但是这是有坑的! contOS 的防火墙和阿里云的安全策略都需要考虑

如果 你直接开始连接 你的svn服务器 出现服务器长时间无回复

就是上述两种情况之一

contOS6.8的防火墙 开启3690端口
```
#开放 3690端口
vim /etc/sysconfig/iptables
#在COMMIT上面加上 以下代码
-A INPUT -m state --state NEW -m tcp -p tcp --dport 3690  -j ACCEPT
```
如果还是不能连接成功
那就是阿里云自带的安全组 需要开放入口方向的3690端口

[网上带图的一个contOS步骤](https://www.cnblogs.com/liuxianan/p/linux_install_svn_server.html/)