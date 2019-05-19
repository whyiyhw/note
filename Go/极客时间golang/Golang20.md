## Golang
-  新技术 第一怎么用?
-  怎么实现?
-  为什么这么实现?
-  有程序员特色的学习方法，针对学习的内容写一点程序。把正在学习的问题的解法，写一个算法实现出来。

- `golang` 可以用来干什么？
    - 服务器编程，以前你是用C 或者c++ 做的事情golang 都可以实现
    如， 处理日志，数据打包，虚拟机处理，文件系统
    - 分布式系统，数据库代理器等
    - 网络编程 包括web 应用 API 下载应用
    - 内存数据库 
    - 云平台 docker K8S

- 环境安装
    - windows [下载](https://studygolang.com/dl/golang/go1.11.windows-amd64.msi)
        - 会拿到一个 `msi` 文件 以管理员身份运行安装就OK
        - `go version` 查看 环境
        - `go env` 查看 `golang` 的环境变量
    - [文档](http://docscn.studygolang.com/pkg/) 
    - contOS7 安装
    ```
    wget -c https://studygolang.com/dl/golang/go1.11.linux-amd64.tar.gz
    tar zvxf go1.11.linux-amd64.tar.gz 
    mv ./go /usr/local/
    vim /etc/profile.d/go.sh
    # 输入
    export PATH=$PATH:/usr/local/go/bin
    :wq
    source /etc/profile.d/go.sh
    # 验证
    go version 
    # 出现 go version go1.11 linux/amd64
    
    # 创建自己的 go 开发目录
    mkdir -m 755 /data/go # data 也是 我自己建的 递归加 R
    # 添加GoPath
    vim /etc/profile.d/gopath.sh
    export GOPATH=/data/go
    :wq
    source /etc/profile.d/gopath.sh
    echo $GOPATH # 测试 是否成功写入GoPath
    
    # 测试 是否能成功运行
    vim /data/go/hello_world.go
    package main
    import "fmt"
    func main() {
        fmt.Println("hello world!")
    }
    :wq
    go fmt /data/go/hello_world.go # 格式化
    go run /data/go/hello_world.go # 直接运行
    ```
    - `godoc -http=:6060`访问`localhost:6060` 自带文档服务 (0.0)骚气

- 
```
package main //一个工程（文件夹里） 有且只有一个 main 包

import "fmt"

func main() {// 一个工程(文件夹里) 有且只有一个 main 函数
	fmt.Println("hello world")
}
Println 返回 打印的字符数,error 会自动在结尾加上换行
所以以上 会返回12个字符数

// unix "\n"换行
// windows "\r\n"回车换行
// mac "\r" 回车
```
- Go的命令行
    - `run`   直接运行
        - `go run hello.go` 
    - `build` 编译
        - `go build hello.go` 
    - `fmt`   格式化
        - `go fmt hello.go` 

- Go 程序的基本结构和要素
    - 
    - 包的概念,导入与可见性
        -  包是一种结构式的方式,每个程序都是由(pkg)包的概念组成,可以使用自身 或者从其他包中导入内容
        -  类似于命名空间,每个.go文件都属于一个包
        -  `package main` 表示一个可独立执行的程序,每个Go应用程序都包含一个`main`包
        -  多个 pkg main 只要有一个 main 就Ok 
        -  如果你打算编译包名不是为 main 的源文件，如 pack1，编译后产生的对象文件将会是 pack1.a 而不是可执行程序
        -  另外要注意所有的包名都应该使用小写字母。
    - 标准库
        -  标准库位于 安装目录下pkg/平台代号/ 下
        -  Go的标准库包含了大量的包,但是你也可以创建自己的包
        -  包的依赖关系决定了其构建顺序。
        -  如果对一个包进行更改或重新编译，所有引用了这个包的客户端程序都必须全部重新编译
        -  如果 A.go 依赖 B.go，而 B.go 又依赖 C.go：

            - 编译 C.go, B.go, 然后是 A.go.
            - 为了编译 A.go, 编译器读取的是 B.o 而不是 C.o
        - 每段代码只会被编译一次
        - 一个Go程序是通过 import 关键词将一组包链接在一起
    - 可见性规则
        - 大写对引入可见 小写仅自己可见 
        - 包名类似命名空间
        - 使用 别名来避免重名
    - 函数
        - 最简单函数`func functionName()`
        - main 函数 既没有参数 也没有返回值
        - 在程序初始化 后 会调用 main.main() 函数
        - 程序正常退出的代码为0 即 Program exited with code 0
        - 如果程序因为异常而退出 就会返回非零值 这个数值可以推断 程序是否成功执行
    - 注释
        - 注释可以被 godoc 收集
        - // 行注释 
        - /* */ 块注释 
    - 类型
        - 基本类型
            - bool string int float
        - 复合类型
            - struct array slice map channel
            - 复合类型的零值为 nil 
            - 可以使用 type 来自定义别名 type xy int   var a xy = 3
        - 只描述 类型的行为
            - interface
    - Go程序的一般执行结构
        - 按顺序导入所有被 main 包引用的其它包，然后在每个包中执行如下流程：
        - 如果该包又导入了其它的包，则从第一步开始递归执行，但是每个包只会被导入一次。
        - 然后以相反的顺序(先进后出)在每个包中初始化常量和变量，如果该包含有 init 函数的话，则调用该函数。
        - 在这之后，main 也执行同样的过程，最后调用 main 函数开始执行程序。
    - 类型转换
        - 由于Go语言不存在隐式转换,因此所有转换必须显示说明
        - 
-  问题一 go 同一个`pkg`内不同文件间函数的调用规则?
    - 对于 `main` 包来说使用 同级下 `main` 包的 变量与函数 在`build` 时 应该 将 所有`main`包的文件 都进行 build 也就是 `build . || build *.go` 不然会报 使用未定义的变量与方法 `eg go build main1.go main2.go`
    - 对与 非 `main` 包的其它包是正常的 不需要指定 应该是 所有的 已经被编译了

- Go 的数据类型
    - 计算机功能是运算 ，运算前 需要确定输入的数
    - 数先被存到内存中，如何存呢？
    - 数据类型作用 告诉编译器这个数（变量） 应该以多大的内存存储

    - 变量 -- 运行期间内可变的量
        - 命名规范
            - 字母，下划线，数字
            - 不能以数字开头
            - 名字不能是关键字和保留字、
            - 区分大小写(大写公用，小写私用)
        - 变量声明 
            -   var a int  //1.声明格式 var 变量名 a 类型 int
            - 只声明没有初始化的 变量 系统会根据声明的类型给默认值零值
            - 声明必须使用
            - 不可重复声明
        - 变量赋值
            - var a int ;  a = 3
            - 也可以 直接 自动推导 var a = 3 a 默认就是 int 3
        - 变量初始化
            - var a int  = 3
            
        - 自动推导类型
            - c := 3 ; fmt.Printf("c type is T%\n", c) // %T 为type类型
            - 同一个变量名 只能 声明一次
        - Println 与Printf的区别
            - Println 是 打印 默认在后面 加上当前系统的换行
            - Printf 是格式化打印 fmt.Printf(,) 可以打印更多的信息 
        -  多重赋值 与匿名变量
            - a,b := 10,20
            - 交换 两个变量的值 a,b = b,a
            - _,a := 10,20 _ 就是匿名变量 //一般配合函数使用 丢弃 多返回值
    - 常量 -- 运行期间内不可变的量
        - const b = 30 //关键字 name  =  值 自动推导 不需要使用 :=
        - 多常量定义 
        ```
        const{
           a = 2
           b = 3
        }
        ```
        - 常量与iota
            - iota 从0开始自动加1
            - 遇到const 重新变为0
        ```
    	const (
	        a = iota
	        b = iota
        )
        const (
	        d = iota
	        e
	    )

	    fmt.Println(a, b, d)
        ```
            - 常量在声明后可以不使用
    - bool
        - 布尔类型
        - 长度为1 
        - 零值为 false
        - 其值不为真即为假，不可以用数值代表 true 或 false
    - byte
        - 字节类型
        - 长度为1
        - 零值为 0
        - uint8 的别名
    - rune
        - 字符类型
        - 长度为4
        - 零值为 0
        - 专门用于 存储unicode编码 等价于 uint32
    - int uint
        - 整型
        - 长度为4或者8
        - uint 就是无符号 永远为正
        - 零值为0
        - 根据操作系统 为32或者64位
    - int8 uint8
        - 整型
        - 长度为1
        - 零值为0
        - -128-127 | 0-255
    - int16 uint16
        - 整型
        - 长度为2
        - 零值为0
        - -32768-32767 | 0-65535
    - int32 uint32
        - 整形
        - 长度为4
        - 零值为0
        - -21亿-21亿 | 0-42亿
    - int64 uint64
        - 整形
        - 长度为8
        - 零值为0
        - ~
    - float32
        - 浮点型（实型）
        - 长度为4 
        - 零值为0.0
        - 小数位精确到7位
    - float64
        - 浮点型（实型）
        - 长度为8
        - 零值为0.0
        - 小数位精确到15位
    - complex64
        - 复数类型
        - 长度为8
        - 零值为 (0+0i) //实数+虚数
    - complex128
        - 复数类型
        - 长度为16
        - 零值为 (0+0i) //实数+虚数
    - uintptr 
        - 整形
        - 4或者8
        - 用来存储指针的uint32或者uint64整数
    - string
        - 字符串
        - 零值 ""
        - utf-8字符串
    - tips 
        - a := "" a的类型一律为 字符串 string
        - a := 'a' a的类型为 (rune) int32
        - var a byte = 'a' a的类型为 (byte) int8
    - 基础类型
        - bool
        - float 浮点
        - rune/byte 字符
        - 大小写相差32 小写数字比较大
        - 字符与字符串
```
var a byte = 'a'
fmt.Printf("a type is %T num is %c %d/n",a,a,a)
a type is uint8 num is a 97/n
%c 以字符表示 %d 以数字表示
```


#### 字符与字符串的区别？
1. 单双引号
2. 字符串由一个或者多个字符组成
3. 字符串都是隐藏了一个结束符号"\0"
4. str := "a" //是由 字符'a' + '\0' 组成的
5. 访问字符串中的元素 可以使用 str := "str" ==str[0]== 为 s ==str[1]== 为 t

- 复数类型
```
 t := 2.1+3.14i`
 fmt.Println("real(t)",real(t),"imag(t)", imag(t))`
 系统函数 ==real()== 取实部  ==imag()== 取虚部
```
##### 格式化的几个标识
    - %c 格式化字符
    - %s 格式化字符串
    - %f 浮点型
    
    - %v 万能ge'shi
##### 从 cli 中取输入
```
	var a int
	fmt.Printf("请输入数字a :")
	// 堵塞等待用户输入
	//fmt.Scanf("%d", &a)
	// Scan 堵塞等待用户输入 自动推导格式 无需格式化
	fmt.Scan(&a)
	fmt.Println("a is", a)
```
##### 类型转换 可以看作函数
1. 不是所有类型都能类型转换,只有兼容的数据格式才能转换
2. bool 类型 不能转换成整型 整型不能 转换成bool
3. 兼容类型 字符型本质上就是 整型 var a byte a= 'a' int(a) 这个时候 就可以转成int 97
5. 范围大的格式 转 范围小的格式 会精度丢失
##### 类型别名
1. 可以给系统 类型 起一个别名
2. 主要用于后期的 复合类型 结构体
##### 运算符号
- 算术运算
    - 加 +
    - 减 - 
    - 乘 *
    - 除 /
    - 取模 %
    - 后自增 ++
    - 后自减 --
- 关系运算 返回 true|false
    - == 全等
    - != 不全等
    - < 小于
    - > 大于
    - <= 小于等于
    - >= 大于等于
- 逻辑运算
    - 非 ! 取反
    - 与 && 同真为真
    - 或 || 有一个为真 那就为真
- 位运算符
    -  按位与 &
    -  按位或 |
    -  异或 ^
    -  左移 << 4 << 2 为 4^2 为16
    -  右移 >> 4 >> 2 结果为1
- 赋值运算符
    - = 普通赋值
    - += 相加后赋值
    - -= 
    - *=
    - /=
    - %=
    - <<=
    - >>=
    - &=
    - ^=
    - |=
- 其他运算符号 
    -  & 取地址运算符 &a 变量a的内存地址
    -  * 取值运算符 *a 指针变量所指向的内存的值
- 运算符的优先级
    - 1. ^ ! 
    - 2. * / % << >> & &^
    - 3. + - | ^
    - 4. == != < <= >= >
    - 5. <-
    - 6. && 
    - 7. ||
##### +号 与 += 在字符 字符串 与 int 类型中的
    - rune 中 + | += 的结果 为 int32
    - 字符串的 + | += 的 结果为 字符串的 拼接
    - int 类型相加 就是普通的 加减
    - float 类型 的运算 有可能会导致精度丢失
##### 流程控制
    - 顺序结构
    - 选择结构
        - if {} 支持一个初始化语句
        - if {} else if {}
        - switch break default fallthrough  case 表达式: 表达式可以为简短式子
    - 循环结构
        - for 循环
            - for 初始条件； 判断条件 ; 条件变化 { }
            - if 的初始化变量 作用域 为 {} 花括号之间
        - range 迭代
            - for i, data := range str {}  for key,value := range arr {} 
    - 跳转语句
        - break 和 continue
            - break 是跳出 本次循环 continue 是跳过 本次循环
            - break 可用于 for swtich select ，continue 只能用于 for 循环
        - goto goto title  title: 需要在一行的顶头
#### 函数
    - 定义格式
```
func FuncName (参数列表) (a type,b type/*返回值列表*/) {
    // 函数体
    
    return a,b
}
```
- 参数列表 不支持默认参数
- 返回值 可以只有类型 没有 变量名
- 如果 只有一个 返回值 且不声明返回值变量 那么你可以省略，包括 返回值的括号
- 如果没有返回值， 那么直接省略 最后的返回信息
- 如果 有返回值 那么必须有 return
    
##### 普通函数
    - 固定 参数
        - 固定参数一定要传参 
    - 不定参数
        - 可能 传入 多个 参数
        - 不定参数 不一定 要传参
        - 不定参数 一定是参数列表的 最后一个参数
        - 不定 参数在 函数中的传递 由前... 改为 后... 
```
func MyFunc(args ...int) {
	for i, data := range args {
		fmt.Println(i, data)
	}
}

func MyFunc1(tem ...int) {
	MyFunc(tem[:1]...) //传递给0-1 不包括下标 1 给 下个函数
}
```
    - 普通有单一返回值 多返回值 就 多写几个
```
func MyFunc1(a, b int) (res int) {
	res = a+b
	return
}
```
##### 递归函数
    - 对于 函数 栈的理解
    - 递归前 调用 是 顺序结构 递归后 调用是 栈结构
```
// 1-100 累加
func MyFunc1(a int) (sum int) {
	if a == 100 {
		return 100
	}
	sum = a
	return sum + MyFunc1(a + 1)
}
```
##### 函数类型 (函数指针)
- 对于 多态 来说
```
// 我规定 传入 两个int 返回一个 int
type FuncType func(int, int) int
// 只要 你能 实现 都是 我这个类型
func Calc(a int, b int, fTest FuncType) int {
	return fTest(a, b)
}
// 这样 在不改变 函数的情况下 函数的扩展性会很好
type 类型名称 func(int, int) int
```
##### 匿名函数与 闭包
- 闭包 函数内部能 捕获外部的 外部 属于同一作用域的 变量与常量
- 在 Go语言中 所有的匿名 函数 都是 闭包
```
	a := 10
	str := "mike"
// 匿名函数自调用
	func(){
		fmt.Println(a,str)
	}()
	
	func(as,b int){
		a = as+a
		fmt.Println(as,b)
	}(10,20)

	fmt.Println(a)//20
```
- 闭包以引用 的形式 来获取外部 变量 一改都改
- 一般函数 在调用时 对 变量分配空间 执行完毕 回收变量
- 闭包函数 不在乎 作用域 只要 还在使用 那么 变量都不会被释放
```
func test() func() int {
	var i int
	fmt.Println(i)
	return func() int {
		i++
		return i * i
	}

}
	ff := test()
	println(ff())
	println(ff())
```

##### 延迟调用 defer
1. defer 的调用
2. defer 在函数 执行完毕时候 多defer 调用 先入后出 栈结构
3. 就算 多个 defer 中 一个defer 发生了 错误 还是 会继续执行
4. defer 与 匿名函数
    - 如果是传参 那就是 先传 不调用
    - 如果 是 不传参 就使用 是引用外面 参数
##### 获取 命令行 参数 
- list := os.Args 
- 这个 list 会获取 执行时 传递 过来的参数
- 执行 hello.exe a b 
- hello.exe,a,b 都是list 列表中的参数
##### 变量作用域
- 局部变量
    - 定义在 { }内的 变量 叫 局部变量
    - 只能在 {} 内有效
    - 执行到 定义变量时分配空间 离开 {} 时销毁
- 全局变量
    - 定义在 函数外部的变量 叫 全局变量
    - 全局 变量 在 函数执行 生命周期内 都可以使用和更改
- 全局变量与 局部变量 同名的情况
    -  使用原则 为 就近原则
#### 工程管理
##### 工作区
- bin src pkg(必须)
- 导入包
    - import () 多个
    - . "fmt" 导入 直接用
    - _ "fmt" 导入后 不使用
    - f "fmt" 起别名
- 同一个 目录 包名要一致
- 同一个 目录 调用 别的 文件的 函数 可以直接调用
- 先执行 导入包的 init() 函数  每个 包的 init 只执行一次 也是 循环
##### 关于三个 目录
src 时 必须的  GOPATH 定义在 src 的 上一级
GOBIN 定义在 src 平级的 bin 
go install 一个 不可执行的文件时候
会在 平级的 pkg 下面 生成 一个 系统变量/.a 静态库

go get
go get会做两件事：
1. 从远程下载需要用到的包
2. 执行go install

go install
go install 会生成可执行文件直接放到bin目录下，当然这是有前提的
你编译的是可执行文件，如果是一个普通的包，会被编译生成到pkg目录下该文件是.a结尾
#### 复合类型
1. pointer 指针
    - 变量
        - 变量的 两层含义 
            - 变量的 内存
            - 变量的 地址
        - 保存 变量的 地址 就是指针
    - `var p *int` `p = &a` `*p = 233`
    - 基本操作
        -  默认值 为 nil 
        -  & 来取变量地址 * 通过指针访问目标
        -  不支持 指针运算 不支持'->'运算 直接 `.` 来访问对象
    - 不要 用来操作没有合法 指向的内存
    - new 函数
        - 申请 一块内存 空间 返回 对应类型的指针
        - 	 `q := new(int)` `*q = 566` `fmt.Println(*q)`
    - 指针 在 函数参数中的 使用
```
func swap(a, b *int) {
	*a, *b = *b, *a
}
swap(&a, &b)
```
2. array 数组
    - 对同一类型 变量的 集合
    - 声明 var a [50]int 声明 [数字] 必须是 常量 不能使用 变量
    - 数组的 初始化
        - 声明定义同时 赋值
        - aa := [5]int{1, 2, 3}
        - 下标 赋值 a := [5]int{1:10, 4:2}
    - 二维 数组 
        - 有多少[] 就是 几维 
```
	var id [5][3]int
	k := 0
	for i, _ := range id {
		for j, _ := range id[i] {
			k++
			id[i][j] = k
		}
	}
	fmt.Println(id)
	
	ids := [2][2]int{{1, 2}, {4 }}
	fmt.Println(ids)
```
    - 数组 比较与 赋值
    - 比较 只能使用 ==  或 !=  比较
    - 赋值 在 类型 一致 时 可以 用 = 赋值
##### 随机数
```
	// 设置 种子
	// 如果 种子一样 每次 运行程序 产生的结果一样
	rand.Seed(time.Now().UnixNano())
	for i := 0; i < 5; i++ {
		//fmt.Println(rand.Int())//随机一个很大的数
		fmt.Println(rand.Intn(100))// 随机100以内的数
	}
```
##### 数组排序
```
func sort(a [10]int)( [10]int){
	n := len(a)
	for i := 0; i < n-1; i++ {
		for j := 0; j < n-i-1; j++ {
			if a[j+1] < a[j]{
				a[j], a[j+1] = a[j+1], a[j]
			}
		}
	}
	return a
}
// 作为函数参数必须声明数组 包括类型与 大小
```
##### 数组 做为函数参数
    - 数组 做 函数 参数时是值传递 所以 数组越大效率越低
    - 解决办法 就是 传递指针
```
func modify(p *[10]int){
	n := len(*p)
	for i := 0; i < n-1; i++ {
		for j := 0; j < n-i-1; j++ {
			if (*p)[j+1] < (*p)[j]{
				(*p)[j], (*p)[j+1] = (*p)[j+1], (*p)[j]
			}
		}
	}
}
```
##### 数组的缺点
    - 数组大小是固定的
    - 数组 做参数时 会传值
#### slice 切片 （一种结构实现 变长数组的功能）
    - 数组和切片的区别
        - [3]int{1,2,3} 数组
        - []int{1,2,3}  切片
        - array[0:3:5] [low,hegh:max] len = hight - low
        - cap = max-low 容量 声明5个空间
        - slice 的 cap 是继承来自原来的slice
        - slice 再 append 之后 如果不足以 填充空间 底层会双倍扩容
        - slice 是一种 数据结构 取自与底层数组的引用

4. map 字典
5. struct 结构体