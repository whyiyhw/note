1. 给定一个一维数组,要求在原数组中移除指定的value,返回数组的长度
```

function(array $array,$value){
    $i = 0;
    $j = 0;
    $n = count($array);
    for($i;$i<$n,$i++){
        if($array[$i] === $value){
            continue;
        }
        $array[$j] = $array[$i];
        $j++;
    }
    return $j;
}
```
2. 在有序数组中,移除重复的元素