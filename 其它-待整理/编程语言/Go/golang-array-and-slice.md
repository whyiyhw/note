---
title: golang base (one)
date: 2019-05-08 21:58:26
categories: golang
tags: 
- golang
---

## 程序入口

- 必须为 `package main`
- 必须是 `func main(){}`
- 文件名称可以不为 `main.go`
- `Go` 中 `main` 函数不支持返回值
- 可以通过 `os.Exit()` 来传出 返回值
- `main` 函数不支持传入参数 可以通过 `os.Args` 来获取

  ```Go
    func main(){// 不支持入参
        fmt.Println("hello world!")
        if len(os.Args) >1 {//通过 os.Args 来获取传入的参数
            for arg,value := range os.Args{
                fmt.Println(arg,value)
            }
        }

        os.Exit(0)
    }
  ```

## 变量，常量以及与其它语言的区别

### 编写测试程序

- 源码文件以 `_test` 结尾： `xxx_test.go`
- 测试方法名以 `Test` 开头 `TestXXX(t *testing.T){...}`
- `go test -v xxx_test.go` 才能输出 `t.Log` 里的文字
- 实现斐波拉契数列

  ```Go
    func TestFibList(t *testing.T) {
        a,b := 1,1
        for i := 0; i < 5; i++ {
            fmt.Println(b)
            a,b = b,a+b
        }
    }
  ```

### 变量与其它静态编程语言的差异

- 赋值可以进行自动类型推断
- 同一个赋值语句中可以对多个变量进行同时赋值

### 常量与其它静态编程语言的差异

- 快速设置连续值 `iota` 遇到下一个 `const` 之前连续递增1,遇到之后变为0
- `iota` 只能在常量中使用
  
  ```Go
    const (
        Monday    = iota + 1 // 1
        Tuesday              // 2
        Wednesday            // 3
    )

    const (
        Readable   = 1 << iota //1 0001
        Writable               //2 0010
        Executable             //4 0100
    )

    const (
        i = iota // i=0
        j = 3.14 // j=3.14
        k = iota // k=2
        l        // l=3
    )

    type ByteSize float64

    const (
        _           = iota             // ignore first value by assigning to blank identifier
        KB ByteSize = 1 << (10 * iota) // 1 << (10*1)
        MB                             // 1 << (10*2)
        GB                             // 1 << (10*3)
        TB                             // 1 << (10*4)
        PB                             // 1 << (10*5)
        EB                             // 1 << (10*6)
        ZB                             // 1 << (10*7)
        YB                             // 1 << (10*8)
    )

  ```

## 数据类型

- 基本数据类型 值类型 初始化时默认会有零值
  - `bool` : `false`
  - `string` : `""`
  - `int int8 int16 int32 int64` : `0`
  - `uint uint8 uint16 uint32 uint64 uintptr` : `0`
  - `byte` : `0` // alias for uint8
  - `rune`: `0` // alias for int32，represents a Unicode code point
  - `float32 float64`:`0`
  - `complex64 complex128`: `(0+0i)`
- 复合类型
  - `pointer function interface slice channel map` : `nil`
  - 对于复合类型, `go` 语言会自动递归地将每一个元素初始化为其类型对应的零值。比如：数组， 结构体
  
### 整型占用字节问题

- int，uint整型：和机器平台有关，最小32位，占用4字节，64位，占用8字节。
  
  ```go
  //机器位数
  cpu := runtime.GOARCH
  t.Log(cpu) // amd64
  //int占用位数
  int_size := strconv.IntSize
  t.Log(int_size) // 64
  ```

### 数值范围

类型 | 长度（字节） | 数值范围
--- |--- |---
int8 | 1 | -128~127 （-2^(8-1) ~ 2^7-1）
uint8 | 1 | 0~255 (0 ~ 2^8-1)
int16 | 2 | -32768~32767
uint16 | 2 | 0~65535
int32 | 4 | -2^31 ~ 2^31-1 (-2147483648~2147483647)
uint32 | 4 | 0~2^32-1 (0~4294967295)
int64 | 8 | -2^63 ~2^63-1
uint64 | 8 | 0～2^63

### 数据类型与其它静态编程语言的差异

- Go语言不允许隐式类型转换
- 别名和原有类型也不能进行隐式类型转换
  - 在某些语言中允许小范围类型向大范围类型转换，因为数据精度不会丢失
  - 大范围向小范围转换会导致精度丢失
  
  ```Go
    type MyInt int64
    func TestDataType(t *testing.T){
        var a int32 = 1
        var b int64
        b = int64(a)
        var c MyInt
        c = MyInt(b)
        t.Log(a,b,c)
    }
  ```

- `Go` 语言不支持指针运算
- `string` 是值类型，其默认的初始值为 `""` 空字符串，而不是`nil`

  ```Go
  func TestPoint(t *testing.T) {
        a := 1
        aPtr := &a
        // aPtr = aPtr + 1 是不被允许的
        t.Log(a, aPtr)// 1 0xc00000a2b8
        t.Logf("%T %T", a, aPtr)// int *int
        var s string
        if s == "" {
            t.Log("string 零值为空字符串")
        }
        t.Log(len(s)) //0
    }
  ```

[golang代码编写最佳实践](https://mp.weixin.qq.com/s/BbZcp5OJSQHNi6nlnu3_eA)

## 运算符号与其它静态编程语言的差异

### 算术运算符

- `+ - * / % 后++ 后--`

### 比较运算符

- `==` `!=` `>` `<` `>=` `<=`
- 在 `golang` 比较数组 如果两个数组的维度相等是可以比较的
  
  ```Go
  a :=[...]int{1,2,3,4} // 数组声明
  b :=[...]int{1,2,4,3}
  c :=[...]int{1,2,3,4}
  t.Log(a == b) // false
  t.Log(a == c) // true
  ```

### 逻辑运算符

- `！`  `&&`  `||`
  
### 位运算符号

- `&` 按位与（A & B） 结果为12 二进制为00001100
- `|` 按位或（A|B）结果为61，二进制为00111101
- `^` 按位异或（A^B）结果为49，二进制为00110001
- `<<`A << 2 结果为240 ，二进制为11110000
- `>>`A >> 2 结果为15 ，二进制为00001111
- `&^` 按位清零 运算符 右边为1 则左边清零 为0则左边为原值

  ```Go
    1 &^ 0 --> 1 // 右边为0 则左边不变
    1 &^ 1 --> 0 // 右边为1 则左边清零
    0 &^ 1 --> 0 // 右边为1 则左边清零
    0 &^ 0 --> 1 // 右边为0 则左边不变
  ```
  
## 第八讲 流程控制