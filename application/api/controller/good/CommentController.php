<?php
namespace app\api\controller\good;

use app\api\service\MemberService;
use app\common\model\good\GoodComment;
use app\common\vo\ResultVo;

class CommentController
{
    public function index()
    {
        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $good_id = request()->get('good_id/d');

        $list = GoodComment::where("good_id", $good_id)
            ->limit($page, $count)
            ->order("create_time DESC")
            ->select();

        if ($list) {
            $member_ids = [];
            foreach ($list as $v) {
                $member_ids[] = $v->member_id;
            }
            $member_map = MemberService::listMemberInfoByMemberIdIn($member_ids);
            foreach ($list as $v) {
                $v->member = $member_map[$v->member_id] ?? [];
            }
        }
        return ResultVo::success($list);

    }

}
