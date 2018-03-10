## 语言的优势
1. 静态类型语言,可直接编译成机械码,不依赖其他库
2. 内置GC 
3. 语言层面支持并发
4. 内置大量的库,网络库很强大

## 应用
1. 服务器编程
2. 分布式系统
3. 网络编程    web api 下载 
4. 云平台

## 第一个程序
```
package main //go语言以包来管理程序

import "fmt" //引入fmt库

func main(){//这里的花括号不能写在下面
    fmt.Println("hello world!")//这里必须是双引号不能是单引号 单引号表示字符 字符又只能为单个的字母
}
```

## 第二个程序
```
//1. 以包作为管理单位
//2. 每个文件必须声明包
//3. 程序必须要有main包
package main

import "fmt" //导入包必须要使用

//入口文件 函数
func main() {
	//这是注释
	/*这是第二种注释*/
	var a, b int = 10, 0 //声明几个赋值几个且只能赋值该类型
	a = 20
	//	var c = 20
	c := "abc"         //自动推导类型
	fmt.Println(a + b) //go语言结尾不要加;
	//%T 打印所属类型
	fmt.Printf("c type is %T\n", c)//c type is string
	fmt.Println("hello itcast")//ln 和f 就是加不加\n 和能按类型来解析变量的区别
}

//简单的命令行
//go run    .go
//go build  编译.go 成.exe

//数据类型的作用
//告诉编译器以多大的内存来存储  方便内存分配管理空间

//命名规范:字母/下划线/数字
//区分大小写 不能用关键字,保留字 区分大小写

//变量 常量
//声明 var a int
// 	变量声明 必须要使用
//	声明不使用 为 0 bool 为 false
//	声明的变量名是唯一的
//  可以声明多个变量

```

## 第三个程序
```
package main

import "fmt"

func main() {
	const (
		a = 10
		b = 1.2
	)

	var (
		c = 10
		d = "3.14"
	)
	//	c, d = 10, "3.14"
	const (
		e = iota//0
		f //1
		g //2 可以省略 按顺序递增
	)
	fmt.Printf("e = %d,f = %d,g = %d\n", e, f, g)//	e = 0,f = 1,g = 2
	const h = iota//0 iota 遇见const变成0
	const i = iota//0
	const (
		j         = iota//0
		k, k1, k2 = iota, iota, iota//1,1,1
		m         = iota
	)
	fmt.Printf("j,k,m,%d,%d,%d,%d,%d\n", j, k, m, k1, k2)// j,k,m,0,1,2,1,1
	fmt.Println("h = ", h)// h =  0
	fmt.Println("i = ", i)// i =  0
	fmt.Printf("a type is %T\n", a)//a type is int
	fmt.Printf("b type is %T\n", b)//b type is float64
	fmt.Printf("a = %d,b = %s\n", c, d)//a = 10,b = 3.14
}

```
## 第四个程序
```
package main

import "fmt"
import "time"

func main() {
	//	var a int
	//	fmt.Printf("请输入变量a: ")

	//阻塞等待用户输入
	//	fmt.Scanf("%d", &a)
	//	fmt.Scan(&a)
	//	fmt.Println("a = ", a)
	//请输入变量a: sss
	//	a = 0
	//请输入变量a: 1235
	//a =  1235

	//类型转换
	var flag bool
	flag = true
	fmt.Printf("flag = %t\n", flag)

	//bool不能转成整形
	//fmt.Printf("flag = %d\n", int(flag))
	//cannot convert flag (type bool) to type int
	//整形不能转成bool --- 不兼容类型
	//	flag = bool(0)
	//	cannot convert 0 (type int) to type bool

	var ch byte//字符也可以算 整形可以强行转换
	ch = 'a'
	var t int
	t = int(ch)
	fmt.Println("t = ", t)

	//类型别名
	type bigint int64
	var a bigint
	fmt.Printf("a type is %T\n", a)

	//关系运算符
	var a1, a2 int = 3, 4
	if a1 < a2 {
		fmt.Println("3 > 4 为 ", a1 > a2)
	}

	if a3, a4 := 5, 4; a3 <= a4 {//循环前可以先赋值 以;结尾
		fmt.Println(" OK ", a3)
	} else if a3 < 5 {
		fmt.Println(" don't OK")
	} else {
		fmt.Println(" I ")
	}

	//fallthrough 接下来的无条件执行
	//a3为一个块级作用域
	//	fmt.Println("请输入数字a:")
	//	fmt.Scan(&a1)
	switch a3 := 4; a3 {
	case 1:
		fmt.Println("为 1")
		break //可以不写默认为break
	case 2:
		fmt.Println("为 2")
		break
	case 3:
		fmt.Println("为 3")
		fallthrough
	case 4:
		fmt.Println("为 4")
		fallthrough
	case 5:
		fmt.Println("为 5")
		fallthrough
	default:
		fmt.Println("为 默认")
	}

	//for 循环
	sum := 0
	for a3 := 1; a3 <= 100; a3++ {
		sum += a3
	}
	fmt.Println(sum)

	//range 迭代 类似于foreach
	as := "asdasd"
	for a3 := 0; a3 < len(as); a3++ {
		fmt.Printf(" %c\n ", as[a3])
	}

	for i, data := range as {
		fmt.Printf("as[%d] = %c\n ", i, data)
	}
	for i, _ := range as {
		fmt.Printf("as[%d] = %c\n ", i, as[i])
	}

	for {
		sum++
		time.Sleep(time.Second)

		if sum == 5055 {
			continue
		}
		if sum == 5057 {
			break
		}
		fmt.Println(sum)
	}
	goto END//goto 只能在一个函数中使用
	fmt.Println("ASDASD")//中间不执行 

END:
	fmt.Println(sum)
}

```