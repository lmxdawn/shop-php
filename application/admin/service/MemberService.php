<?php

namespace app\admin\service;

use app\common\model\member\Member;
use app\common\model\member\MemberQq;
use app\common\model\member\MemberWeChat;
use app\common\utils\PublicFileUtils;
use think\Model;

class MemberService
{

    /**
     * 批量获取用户信息
     */
    public static function listMemberInfoByMemberIdIn($member_ids) {
        if (empty($member_ids) || !is_array($member_ids)) {
            return [];
        }
        $member_ids = array_unique($member_ids);

        $members = Member::whereIn("member_id", $member_ids)->field("member_id,name,avatar")->select();

        $members_map = [];
        foreach ($members as $v1) {
            $v1["avatar"] = PublicFileUtils::createUploadUrl($v1["avatar"]);
            $members_map[$v1["member_id"]] = $v1;
        }

        return $members_map;
    }

}