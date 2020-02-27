<?php


namespace app\api\service;


use app\common\model\member\MemberWeChat;
use app\common\model\user\UserMp;
use app\common\model\user\UserWeChat;
use app\common\service\StatisticService;
use app\common\service\StudyPackageStatisticService;
use app\common\utils\MemberUtils;
use app\common\utils\UserUtils;

class MemberLoginService
{
    /**
     * 微信公众号登录
     */
    public static function weChatMp($wap_user_info, $infoKey)
    {
        $unionid = $wap_user_info[$infoKey];
        $openid = $wap_user_info['openid'];
        $user_we_chat = MemberWeChat::where("unionid", $unionid)
            ->field("member_id")
            ->find();
        $is_new = 0;
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            $is_new = 1;
            // 未查询到信息，创建
            if (!$user_we_chat) {
                $user_we_chat = new MemberWeChat();
                $user_we_chat->unionid = $unionid;
                $user_we_chat->create_time = date("Y-m-d H:i:s");
            }
            $data = [];
            $data["name"] = $wap_user_info['nickname'];
            $data["avatar"] = $wap_user_info['headimgurl'];
            $data["sex"] = $wap_user_info['sex'];

            $member_id = MemberService::createUser($data, $user_we_chat);
            if (!$member_id) {
                return false;
            }
        }

        $member_id = $user_we_chat->member_id;
        $login_data = [];
        $login_data["mp_openid"] = $openid;
        $token = MemberUtils::setLoginRedis($member_id, $login_data);

        $res_data = [];
        $res_data["member_id"] = $member_id;
        $res_data["token"] = $token;
        $res_data["is_new"] = $is_new;

        return $res_data;
    }

    /**
     * 微信小程序登录
     */
    public static function weChatApplet($wax_user_info, $infoKey) {
        $openid = $wax_user_info[$infoKey];
        $user_we_chat = MemberWeChat::where("unionid", $openid)
            ->field("member_id")
            ->find();
        $is_new = 0;
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            $is_new = 1;
            // 未查询到信息，创建
            if (!$user_we_chat) {
                $user_we_chat = new MemberWeChat();
                $user_we_chat->unionid = $openid;
                $user_we_chat->create_time = date("Y-m-d H:i:s");
            }
            $data = [];
            $data["name"] = $wax_user_info['nickName'];
            $data["avatar"] = $wax_user_info['avatarUrl'];
            $data["sex"] = $wax_user_info['gender'];

            $member_id = MemberService::createUser($data, $user_we_chat);
            if (!$member_id) {
                return false;
            }
        }

        $member_id = $user_we_chat->member_id;
        $device = request()->cookie("device");
        $token = MemberUtils::setLoginRedis($member_id, ["device" => $device]);
        $res_data = [];
        $res_data["member_id"] = $member_id;
        $res_data["token"] = $token;
        $res_data["is_new"] = $is_new;
        return $res_data;
    }
}