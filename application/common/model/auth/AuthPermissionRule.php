<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 .
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎明晓 <lmxdawn@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\model\auth;

use think\Model;

/**
 * 权限规则表
 */
class AuthPermissionRule extends Model
{

    //    protected $pk = 'id';

    public static function getLists($where,$order){
        $lists = self::where($where)
            ->field('id,pid,name,title,status,condition,listorder')
            ->order($order)
            ->select();
        return $lists;
    }

}
