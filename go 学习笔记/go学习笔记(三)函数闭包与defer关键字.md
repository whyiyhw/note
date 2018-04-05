函数闭包
```
package main

import "fmt"

func test1() int {
	var x int
	x++
	return x * x
}

func test2() func() int {
	var x int //
	return func() int {
		x++
		return x * x
	}
}

func main() {
	a := 20
	b := "string"
	//函数闭包
	func() {
		//闭包以引用方式调用外包变量
		a = 30
		b = "str"
		fmt.Printf("a = %d,b = %s\n", a, b)
		//a = 30,b = str
	}() //()自调用
	fmt.Printf("a = %d,b = %s\n", a, b)
	fmt.Println(test1()) //1
	fmt.Println(test1()) //1
	f := test2()         //返回的是一个闭包函数
	//闭包不关心捕获了的变量和常量是否超出了作用域
	//所以只要闭包还在使用它,这些变量就还会存在
	fmt.Println(f()) //1
	fmt.Println(f()) //4
	fmt.Println(f()) //9
	fmt.Println(f()) //16
} //a = 30,b = str

```
defer关键字
```
package main

import "fmt"

func main() {
	//defer 的使用
	//defer 类似于析构函数
	fmt.Println("aaaaaaaaa")
	defer fmt.Println("bbbbbbbbb")
	fmt.Println("ccccccccc")
	//aaaaaaaaa
	//ccccccccc
	//bbbbbbbbb
	//多个defer的执行顺序
	//先写后出 不管发生什么错误 defer都能被调用
	defer fmt.Println("ddddd")
	defer fmt.Println("fffff")
	var x int
	res := 100 / x             //在此处程序崩溃
	fmt.Println("x = ", res)   //如果 把 此处也改为defer 那么就算程序出错也会执行
	defer fmt.Println("eeeee") //在此处不执行
	//aaaaaaaaa
	//ccccccccc
	//fffff
	//ddddd
	//bbbbbbbbb
}

```
defer关键字与函数闭包时的传参问题
```
package main

import "fmt"
import "os"

func main() {
	//defer和闭包函数 传值问题
	a := 10
	b := 20
	defer func(a, b int) {
		fmt.Printf("a = %d,b =%d\n", a, b)
	}(a, b) //传参时为 a=10 b=20
	defer func() {
		fmt.Printf("a = %d,b =%d\n", a, b)
	}() //不传参时为 a=22 b=33
	a = 22
	b = 33
	fmt.Println("a = ", a, "b =", b)

	//接受用户传递的参数
	list := os.Args

	n := len(list)
	//循环
	for i := 0; i < n; i++ {
		fmt.Printf("list[%d] = %s\n", i, list[i])
	}
	//迭代
	for i, data := range list {
		fmt.Printf("list[%d] = %s\n", i, data)
	}
	//	fmt.Println("n = ", n)
}

```
