<?php
/**
 *  实现一个通用的 general_filer 方法，逐步代替 filter 方法, 增强可扩展性;
 */

function general_filter($list, $substrs) {
    /**
        检查字符串是否含有特定的子字符串
        @param $list array      源字符串列表
        @param $substrs array   特定字符串列表
        @return array   含有特定字符串的源字符串列表
    */

    $ret = array();
    foreach($list as $str) {
        foreach($substrs as $substr) {
            if (strpos($str, $substr) !== false) {
                $ret[] = $str;
            }
        }
    }

    return $ret;
}

function filter($list, $check, $check2=null) {
    // 重构 filter 保证输入输出不变
    $checks = array($check);
    if($check2) {
        $checks[] = $check2;
    }

    return general_filter($list, $checks);
}
?>
