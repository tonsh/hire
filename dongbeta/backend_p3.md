#### Part III

##### 问题说明：

1. 参数建议：

   仅从方法定义上，参数的命名显得不合理，如 filter($arr, $check) 读代码的人第一反应是 ** 这个方法实现的功能大概是想从一个数组里检查些什么 **，这与函数实现的目的相符；但是 filter($arr, $check, $check2) 就让人有些疑惑 $check2 是在做什么？ 这种情况可以把 $check, $check2 参数合并为一个列表 如 filter($arr, $checks), 增强**可扩展性**;

1. $i 变量没有初始化。

   遇到变量没有初始化总有些不置可否; 测试了一下发现PHP 居然能顺利运行。即便如此，还是建议养成变量初始化的好习惯。
    
1. ```if (strpos($arr[$i], $check))``` 逻辑错误

   应改为 ```if(strpos($arr[$i], $check) !== false)``` 来代替。根据 PHP 文档，strpos 可能返回 >= 0 的偏移量或 false，当 strpos($arr[$i], $check) === 0 时, 上述判断会有误。

1. ```while($i < count($arr))``` 时 count 方法被重复执行（但没重复计算), 可以将 count 在外层赋值或使用 foreach 代替 while 循环。

重构结果请查看 part3.php