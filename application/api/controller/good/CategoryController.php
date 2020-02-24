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

        foreach ($lists as $k => $v) {
            $v['pic_url'] = PublicFileUtils::createUploadUrl($v['pic']);
        }

        $list = TreeUtils::cateMerge($lists,'id','pid',0);

        return ResultVo::success($list);

    }

}
