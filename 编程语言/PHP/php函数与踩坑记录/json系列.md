# json_decode()#php5.2开始使用

- 入参1为 `utf8` 格式的字符串，入参二负责解析成 `array` 或是 `object` 这个对象是`stdClass`对象的，入参3负责解析深度

  - 因为字符串不是合法的json格式的串, 所以应该出错, 返回 `NULL` 。如：

  ```php
   ## 从php5.2开始
  php -r "var_dump(json_decode('laruence'));" #输出 NULL
  ```

  - 对于 `numeric_string`, 在php5.2-5.6返回 `numeric_string` 的数值形式：

  ```php
  ## 从php5.2-php5.6 #输出 int(16180)
  php -r "var_dump(json_decode('0x3f34'));"
  ## 从php7开始 #输出 NULL
  ```

## `json_last_error()` #`PHP5.3`开始使用

- 返回一个整型 来验证 `json` 转换是否正确

## `json_encode()`#`php5.2`开始使用

- 入参1除了 `resource` 类型之外，可以为任何数据类型。
- 入参2控制传入的 编码 最常用的 `JSON_UNESCAPED_UNICODE` 控制显示正常多字节文字

- 返回值 正确返回 `string` 错误返回 `FALSE`

## `json_last_error_msg()`# `PHP 5 >= 5.5.0`

- json_last_error_msg — Returns the error string of the last json_encode() or json_decode() call
- 返回json解析与编码中的错误信息
- `php -r` 可以直接在命令行执行 `php` 代码
- 详情可看 json.php