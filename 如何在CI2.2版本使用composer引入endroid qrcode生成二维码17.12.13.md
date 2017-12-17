composer 如何 安装就不说了

使用[packagist.org](https://packagist.org/)来进行包的查找

输入 ==endroid/qrcode== 进行搜索 点击进入

我在CI框架 根目录下 创建了public目录 在public下

使用命令进行包的下载
```
composer require endroid/qrcode
```
关键是 下载了 如何 在项目 中 进行引入

composer 提供了一种 快速便捷的方式 对 包中的文件进行引入

```
require './vendor/autoload.php';
```
这样 就可以直接使用 命名空间 对包中所有的类 进行引入

在CI中 我使用了public目录所以是

```
require './public/vendor/autoload.php';
```
但是这个引入必须要在 入口文件 引入框架文件之前所以在index.php中
```
require './public/vendor/autoload.php';
require_once BASEPATH.'core/CodeIgniter.php';
```
然后在需要进行生成 二维码的文件中

```
use Endroid\QrCode\QrCode;
$qrCode = new QrCode();

$qrCode   ->setText('这是一个生成二维码');//设置文字
$qrCode   ->setSize(300);//长宽
$qrCode   ->setPadding(10);//填充
$qrCode   ->setErrorCorrection('high');//错误等级
$qrCode   ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);//二维码颜色
$qrCode   ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);//二维码背景色
$qrCode   ->setLabel('Scan the code');//二维码下面的文字
$qrCode   ->setLabelFontSize(16);//字号
$qrCode   ->setImageType(QrCode::IMAGE_TYPE_PNG);//生成的二维码图片类型

// 设置输出的header头
header('Content-Type: '.$qrCode->getContentType());
$qrCode->render();

// 保存一个文件
//$qrCode->save('qrcode.png');
//也可以直接展示
$response = new Response($qrCode->get(), 200, ['Content-Type' => $qrCode->getContentType()]);
```
这样一个简单的二维码 就可以生成了

实际上 可以将 这些代码写入公共文件中 封装成一个函数 就可以直接 进行调用生成了

![QR Code](http://endroid.nl/qrcode/Life%20is%20too%20short%20to%20be%20generating%20QR%20codes.png?label=Scan%20the%20code)