变量作用域
```
```
## 工作区概念

Go代码必须放在工作区

工作区一般分为　
1. src  源代码放置区
2. pak  平台库放置区
3. bin  编译可执行文件放置区

一个工作区只有一个main包
//包名必须一致

//设置GOPATH 环境变量

//go env 可查看GOPATH路径是否正确

//同一个目录下 函数可以直接调用 无需导入包

一个main包下有且只有一个main函数(入口函数)
```
目录
---main.go
---ceshi
------ceshi.go

package main

import (
	"ceshi"
)

func main() {
	//	ceshi.Ceshi()
	ceshi.Ceshi()
}

//同级目录下 申明包名一致可以直接调用

ceshi.go
package ceshi

import (
	"fmt"
)

func Ceshi() {//首字母大写表示共用函数
                //首字母小写表示私有函数 不能被调用
	fmt.Println("say hello")
}

```
## 导包的几种方式
```
package main

//第一种导包方式
//import "fmt"
//import "os"

//第二种导包方式 常用
//import (
//	"fmt"
//	"os"
//	_ "http"
//)

//第三种 点操作
//import . "fmt" //调用函数,无需包名

//包取别名
//import io "fmt"

//忽略包
//import _ "fmt"//不使用不会报错

func main() {
	//	Println("this is . action") 无需包名
	//	io.Println("this is . action")取别名

}
```
## init函数
init函数 是在包被引入时调用 类似于构造方法
```
func init(){
    
}
//会先执行导入包的init 函数 自身的init函数反而是最后执行
```
go install 的使用前提是 必须要配置GOPATH 和GOBIN路径与源代码路径一致
如E:\Go\fir\  E:\Go\fir\bin\  

并且配置的环境变量后面不能加;