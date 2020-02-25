<?php
namespace app\api\controller\good;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\model\good\GoodCategory;
use app\common\utils\PublicFileUtils;
use app\common\utils\TreeUtils;
use app\common\vo\ResultVo;

class CategoryController
{
    public function index()
    {

        $where = [];
        $lists = GoodCategory::where($where)
            ->order("sort DESC")
            ->order("id ASC")
            ->select();

        $temp_list = [];
        $temp_list[] = [
            "id" => -1,
            "name" => "热卖爆款",
            "pid" => 0,
            "is_recommend" => 0,
            "is_show" => 1,
            "level" => 1,
            "pic" => "",
            "pic_url" => "",
            "sort" => 0,
            "create_time" => "2020-01-15 11:05:03",
            "update_time" => "2020-01-15 11:05:03",
        ];
        foreach ($lists as $k => $v) {
            $v['pic_url'] = PublicFileUtils::createUploadUrl($v['pic']);
            $temp_list[] = $v;
        }

        $list = TreeUtils::cateMerge($temp_list,'id','pid',0);

        return ResultVo::success($list);

    }

}
