<?php
$json = '{"foo-bar": 12345}';

$obj = json_decode($json);
print $obj->{'foo-bar'}; // 12345

print  get_class($obj); // 默认转换成 stdClass 类的一个对象

// 名称与值都必须被双引号包起来
// 单引号不能被验证
$bad_json = "{ 'bar': 'baz' }";
var_dump(json_decode($bad_json)); // null

// 名字必须用双引号包起来
$bad_json = '{ bar: "baz" }';
print json_decode($bad_json); // null

// 多了一个逗号
$bad_json = '{ "bar": "baz", }';
var_dump(json_decode($bad_json)); // null

// 正确的值
$good_json = '{ "bar": "233" }';
var_dump(json_decode($good_json)->bar); // string(3)"233"

// range 除了可以生成正序也可以生成倒序数组
var_dump(range(4, 2)); //array(3) { [0]=> int(4) [1]=> int(3) [2]=> int(2) }

var_dump((int)json_decode('{"number":"0x3f34"}')->number);


var_dump(json_encode(array(),JSON_UNESCAPED_UNICODE,-1)); // false

//var_dump(json_last_error() === JSON_ERROR_NONE);
// 这个为自定义的 返回错误
switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo ' - No errors';
        break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
        break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
        break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
        break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
        break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
    default:
        echo ' - Unknown error';
        break;
}

echo PHP_EOL;

var_dump(json_last_error_msg());