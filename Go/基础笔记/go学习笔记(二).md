## 函数
```
package main

//函数的定义

import "fmt"

//无参数 无返回值 函数
func MyFunc() {
	a := 666
	fmt.Println("a = ", a)
}

//有参数 无返回值
func MyFunc01(a, b int) {
	fmt.Println("a = ", a, b)
}

// 不定参数 无返回值  不定参数 只能放在形参中的最后一个参数
// 固定参数一定要传参 不定参数不一定要传参
func MyFunc02(args ...int) {
	fmt.Println("len(args) = ", len(args))
	//	for i := 0; i < len(args); i++ {
	//		fmt.Println("i = ", i, "\n args[i]", args[i])
	//	}
	for i, data := range args {
		fmt.Println("i = ", i, "\n args[i]", data)
	}
}

//不定参数扩展
func MyFunc03(args ...int) {
	for i, data := range args {
		fmt.Println("i = ", i, "data = ", data)
	}
}

func MyFunc04(args ...int) {
	MyFunc03(args...)
}

func MyFunc05(args ...int) {
	MyFunc03(args[2:]...) //从下标2开始传递
}
func MyFunc06(args ...int) {
	MyFunc03(args[:0]...) //从下标0到2 不包括2 传递
}

//无参数 有单个返回值
func MyFunc07() int {
	a := 666
	return a
}
func MyFunc08() (res int) {
	res = 666
	return
}

//无参数 有多个 返回值
func MyFunc09() (a, b, c int) {
	a, b, c = 111, 222, 233
	return
}
func main() {
	MyFunc()
	b := 23
	MyFunc01(b, b)
	MyFunc02(b)
	MyFunc02(b, b, b)
	MyFunc04(b, b, b)
	MyFunc05(b+1, b+2, b+3)

	//	b = MyFunc07()

	fmt.Println("retrun ", MyFunc08())
	a, b, c := MyFunc09()
	fmt.Println("retrun ", a, b, c)
}

```

## 比较二者中的较大值
```
package main

import "fmt"

func max(a, b int) (max int) {
	if a > b {
		max = a
	} else {
		max = b
	}
	return
}
func main() {
	a := 0
	b := 0
	fmt.Println("请输入第一个值")
	fmt.Scan(&a)
	fmt.Println("请输入第二个值")
	fmt.Scan(&b)
	fmt.Println("二者中的较大数为 ", max(a, b))
}

```