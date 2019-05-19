-  变量
    - 声明
        +  
          ```
          var v1 , v2 string//一次性声明多个 
          var v3 [10]int // 数组
          var v4 []int // 数组切片 (可变长)
          var v5 struct {
            f int //声明结构体
          }
          var v6 *int // 指针
          var v7 map[string]int // map，key为string类型，value为int类型
          var v8 func(a int) int //声明函数
          ```
          ---
          变量声明后必须使用 否则会报错  ==declared and not used==
    - 初始化
        +
        ```
        var v1 int = 10 // success
        var v2 = 10 // success
        v3 := 10 // success
        ```
    - 赋值
    - 匿名变量
-  常量
-  类型
-  流程控制
-  函数
-  错误处理