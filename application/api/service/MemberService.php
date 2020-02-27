<?php

namespace app\api\service;

use app\common\model\member\Member;
use app\common\model\member\MemberQq;
use app\common\model\member\MemberWeChat;
use app\common\utils\PublicFileUtils;
use think\Model;

class MemberService
{
    /**
     * 创建用户
     * @param $data
     * @param Model $member_third
     * @return bool
     */
    public static function createUser($data, &$member_third = null) {
        $name = !empty($data["name"]) ? $data["name"] : "U_" . time();
        $avatar = !empty($data["avatar"]) ? $data["avatar"] : "";
        $sex_str = !empty($data["sex"]) ? $data["sex"] : "";
        $sex = intval($sex_str);
        if ($sex != 1 && $sex != 2) {
            $sex = $sex_str == "男" ? 1 : ($sex_str == "女" ? 2 : 0);
        }
        // 创建用户
        $member = new Member();
        $member->name = $name;
        $member->avatar = $avatar;
        $member->sex = $sex; // 创建时不归属性别
        $member->create_time = date("Y-m-d H:i:s");
        $member->modified_time = date("Y-m-d H:i:s");
        if ($member_third) {
            if ($member_third instanceof MemberWeChat) {
                $member->has_we_chat = 1;
            } else if ($member_third instanceof MemberQq) {
                $member->has_qq = 1;
            }
        } else if (!empty($data["mobile"])) {
            $member->mobile = $data["mobile"];
        }
        $commit = $member->save();
        if (!$commit) {
            return false;
        }
        $member_id = $member->member_id;
        if ($member_third) {
            $member_third->member_id = $member_id;
            $commit = $member_third->save();
            if (!$commit) {
                return false;
            }
        }

        return $member_id;
    }

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