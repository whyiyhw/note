---
title: 读http权威指南(综述一)
date: 2019-02-05 16:10:34
categories: http权威指南
tags:
- http
---
## HTTP 的概述
  - 全名为 Hypertext Transfer Protocol 超文本传输协议
  - web
    - 特点 基于tcp的 http协议 保证传输安全,无需考虑丢包
    - web 一般分为客户端(浏览器)和服务端(apache/nginx)
    - 我们可以将响应抽象为一个对象
      - 响应对象,对象类型,对象长度,以及其他一些信息给客户端
  - 资源
    - web 上一切的东西都是资源(哲学0.0)
    - web 服务器 是 web 资源的宿主,所有的 http 请求,都是为了得到一个资源响应
    - 媒体类型
      - 既然所有东西都是资源,文本,图片,视频...
      - 那么浏览器与服务器怎么分辨呢?
        - http 会给每种 要通过 web传输的对象打上 MIME 类型(MIME type)的数据格式标签
        - 当web 浏览器从服务器取回一个对象时,会去查看相关的MIME 类型,根据MIME 类型去进行相应的处理
        - 例如
          - html 文档对应的是 text/html
          - 普通ASCII 文本文档 为 text/plain
          - JPEG 为 image/jpge  GIF 为 image/gif
          - Apple 的 QuickTime 电影为 video/quicktime 类型
        - 如 nginx 会维护一个 MIME type 的文件 里面为 所有支持的MIME 类型
  - URI URL 与URN 的关系
    - 所有的资源都是存在于web 服务器上,那么每一个资源对应都存在一个名称,Uniform Resource Identifier  服务器资源名被称为 统一资源标识符URI
    - 统一资源定位符URL 是资源标识符最常见的形式
      - URL 描述了一台特定服务器上某资源的特定位置.
      - URL 一般遵循一种标准格式,包括三个部分
        - 第一部分被称为方案(scheme) 说明了访问资源所使用的协议类型.这部分通常就是HTTP协议(http://)
        - 第二部分给出了服务器对应的域名地址 比如(www.google.com)
        - 第三部分指定了web 服务器上的某个资源(/index.html)
        - 现在几乎所有的URI 都是URL
      - URN 也是 URI的一种,目标是只存在名字无需域名即可获取,目前尚在试验阶段
  - 事务
    - 基本
      - 一个HTTP事务是由一个http请求和一个http响应组成的
      - 这种通信是通过一种叫HTTP报文的格式化数据来完成的
    - 方法
      - HTTP 支持不同的请求命令,这些命令被称为 HTTP 请求方式 (http method)
      - GET POST PUT DELETE HEAD
      - 每个方法都有对应的含义
  - 状态码
    - 每个响应都会携带 一个状态码 来告知客户端请求是否成功
    - 200 301 302 404
- 报文