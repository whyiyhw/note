## 指针 pointer 默认nil
```
func swap(c, d *int) {
	*c, *d = *d, *c
}

func Pointers() {
	var a int = 10
	//每个变量有两层含义 变量的内存 和变量的地址
	fmt.Printf("a = %d\n", a)
	//a = 10 变量的值
	fmt.Printf("&a = %v\n", &a)
	//&a = 0xc04204a088 变量的地址
	//保存变量的地址 我们就使用指针类型
	var p *int
	p = &a
	fmt.Printf("p = %v\n", p) //p = 0xc04204a088
	*p = 666                  //*p操作的不是p的内存 是p所指向的内存
	fmt.Printf("a = %v\n", a) //a = 666

	//不要操作没有合法指向的指针
	//	var　s *int
	//	s =nil
	//	fmt.Println("s = ",s)

	//new 函数 申请一个可以操作的内存空间
	//	b := 20
	var s *int
	//	s = &b//可以这样指向
	s = new(int) //申请一个int类型的指针(内存空间)
	*s = 666
	fmt.Println("s = ", s)
	//q := new(int) 自动推导类型
	//Go语言动态申请 内存  但是不用手动释放
	//Go会自动释放内存

	c, d := 10, 20
	//通过一个函数交换a和b的内容
	swap(&c, &d) //址交换
	fmt.Println("c =", c, "d =", d)
}
```
## 数组 array 默认0 缺点必须要声明数组长度 传递时是值传递
```
package main

import (
	"fmt"
	"math/rand"
	"time"
)

func getrand(a, b int) int {
	rand.Seed(time.Now().UnixNano())
	var c int
	for i := 0; i < a; i++ {
		c = rand.Intn(b)
	}
	return c
}

func Arrays() {
	//	id1 := 1
	//	id2 := 2
	//	id3 := 3
	//数组 同一种类型变量的集合
	var id [50]int

	//操作数组通过下标操作
	for i := 0; i < len(id); i++ {
		id[i] = i + 1
		fmt.Printf("id[%d] = %d\n", i, id[i])
	}
	//定义数组
	var arr1 [3]int
	//var arr2 [5]int
	//数组元素个数必须是常量
	//	n := 10
	//	var arr3 [n]int//保存
	//	arr1[0] = 1
	//	arr1[1] = "string"//cannot use "string"
	//	fmt.Printf("arr[1] =%s\n", arr1[1])
	for i := 0; i < len(arr1); i++ {
		arr1[i] = i + 1
	}
	for key, item := range arr1 {
		fmt.Printf("arr[%d]  =%d\n", key, item)
	}
	//数组的初始化 定义并赋值
	//	var arr4 [5]int = [5]int{1, 2, 3, 4, 5} //全部初始化
	//	fmt.Println("arr4 =", arr4)             //arr4 = [1 2 3 4 5]
	//	arr5 := [5]int{1, 2, 3, 4, 5}//自动推导类型
	//	fmt.Println("arr5 =", arr5)

	//部分初始化 没有被初始化的元素为0
	//	arr6 := [5]int{1, 2, 3}
	//	fmt.Println("arr6 =", arr6)//arr6 = [1 2 3 0 0]
	//	arr7 := [5]int{1: 2, 4: 5}
	//	fmt.Println("arr7 =", arr7)//arr7 = [0 2 0 0 5]

	//多维数组的使用 有几个[]就是几维
	//有几维就循环几次
	var arr8 [3][4]int
	//	fmt.Println("arr8 len is", len(arr8[1])) //4
	//	fmt.Println("arr8 len is", len(arr8))    //3
	for key, value := range arr8 {
		for k, v := range value {
			_ = v
			arr8[key][k] = key*len(value) + k
		}
	}
	fmt.Println("arr8 = ", arr8) //arr8 =  [[0 1 2 3] [4 5 6 7] [8 9 10 11]]
	arr9 := [2][3]int{{2, 3, 4}, {1, 2, 3}}
	fmt.Println("arr9 =", arr9) //arr9 = [[2 3 4] [1 2 3]]

	//数组比较和赋值
	array1 := [3]int{1, 2, 3}
	array2 := [3]int{1, 2, 3}
	//只有 == 和 !=
	fmt.Println("array1 == array2", array1 == array2) // true
	//同类型的数组是允许赋值的
	array1 = array2
	num := getrand(2, 1000)
	//取随机数 第一个参数是第几次的随机数 第二个参数是 取多大范围的随机数
	fmt.Println("num =", num)

	//数组做函数参数
	func(array [3]int) {
		fmt.Println("array[1] = ", array[1])
	}(array1) //array[1] =  2
	//数组指针做函数参数
	func(a *[3]int) {
		(*a)[1] = 20
	}(&array1)
	fmt.Println("array1 = ", array1) //array1 =  [1 20 3]
}

```
## 切片 slice 默认nil  引用类型 是一种结构 

结构可以理解为可变长数组 
```
``` 
## 字典 map 默认nil   引用类型
```
```
## 结构体 struct 
```
```