<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/7/20
 * Time: 15:36
 */

namespace app\common\utils;


/*
 * 树形结构的工具类
 */
class TreeUtils
{

    public static $icon = ['│', '├', '└'];

    /* 整合多维数组 */
    public static function cateMerge($arr, $idName, $pidName, $pid = 0, $isShowEmpty = false)
    {
        $result = array();
        foreach ($arr as $v) {
            if ($v[$pidName] == $pid) {
                $children = self::cateMerge($arr, $idName, $pidName, $v[$idName], $isShowEmpty);
                if ($children && $isShowEmpty) {
                    $v['children'] = $children;
                }
                $result[] = $v;
            }
        }
        return $result;
    }

    /* 分类数型图 */
    public static function cateTree($arr, $idName, $pidName, $pid = 0, $level = 0, $html = '&nbsp;&nbsp;|--') {
        if (empty($arr)) {
            return array();
        }
        $result = array();
        $total  = count($arr);
        $number = 1;
        foreach ($arr as $val) {
            $tmp_str = str_repeat(self::$icon[0] . '&nbsp;', $level > 0 ? $level - 1 : 0);
            if ($total == $number) {
                $tmp_str .= self::$icon[2];
            } else {
                $tmp_str .= self::$icon[1];
            }
            if ($val[$pidName] == $pid) {
                $val['level'] = $level + 1;
                $val['html'] = '&nbsp;' . ($level == 0 ? '' : '&nbsp;' . $tmp_str . "&nbsp;");
                $result[] = $val;
                $result = array_merge($result, self::cateTree($arr, $idName, $pidName, $val[$idName], $val['level'], $html));
            }
            $number++;
        }
        return $result;
    }


    /* 查找它所有的上级分类 */
    public static function queryParentAll($arr, $idName, $pidName, $id)
    {
        $pids = array();
        while($id != 0){
            foreach($arr as $v){
                if($v[$idName] == $id){
                    $pids[] = $v[$idName];
                    $id = $v[$pidName];
                    break;
                }
            }
        }
        return $pids;
    }
}