<?php


namespace app\api\controller\member;


use app\api\controller\CheckLoginController;
use app\common\enums\ErrorCode;
use app\common\model\member\Member;
use app\common\model\member\MemberWeChat;
use app\common\model\wx\WeChat;
use app\common\model\wx\WeChatApplet;
use app\common\model\wx\WeChatWap;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Hook;

class MemberController extends CheckLoginController
{

    /**
     * 获取用户信息
     */
    public function info() {

        $info = Member::where("member_id", $this->member_id)
            ->field("member_id,name,avatar,sex,mobile,has_we_chat,has_qq,cart_count")
            ->find();

        $info->avatar = PublicFileUtils::createUploadUrl($info->avatar ?? "");


        $res = [
            "member_id" => $info->member_id,
            "name" => $info->name,
            "avatar" => PublicFileUtils::createUploadUrl($info->avatar ?? ""),
            "sex" => $info->sex,
            "mobile" => $info->mobile,
            "has_we_chat" => $info->has_we_chat,
            "has_qq" => $info->has_qq,
            "cart_count" => $info->cart_count,
        ];
        return ResultVo::success($res);

    }

    /**
     * APP微信更新用户信息
     */
    public function updateByWeChatApp()
    {
        $unionid = Hook::exec('app\\common\\behavior\\CheckUserLoginWeChatBehavior', []);
        $user_we_chat = MemberWeChat::where("unionid", $unionid)
            ->field("member_id")
            ->find();
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $data = request()->post();
        $name = !empty($data["name"]) ? $data["name"] : "U_" . time();
        $avatar = !empty($data["avatar"]) ? $data["avatar"] : "";
        $sex_str = !empty($data["sex"]) ? $data["sex"] : "";
        $sex = intval($sex_str);
        if ($sex != 1 && $sex != 2) {
            $sex = $sex_str == "男" ? 1 : ($sex_str == "女" ? 2 : 0);
        }
        $member_id = $user_we_chat->member_id;
        Member::where("member_id", $member_id)->update([
            "name" => $name,
            "avatar" => $avatar,
            "sex" => $sex,
        ]);
        return ResultVo::success();
    }

    /**
     * 微信小程序更新信息
     */
    public function updateByWeChatApplet()
    {
        $code = request()->post("code");
        $encryptedData = request()->post("encryptedData");
        $iv = request()->post("iv");
        // 获取小程序登录信息
        $wax_user_info = WeChatApplet::getInstance(WeChat::CONF_NAME_MINIAPP)->getUserInfo($code, $encryptedData, $iv);
        $infoKey = 'openId'; // 取值为：openId unionid
        if ($wax_user_info === false || empty($wax_user_info[$infoKey])) {
            return ResultVo::error(ErrorCode::DATA_NOT, "获取小程序用户信息失败");
        }
        $unionid = $wax_user_info[$infoKey];
        $nickName = $wax_user_info['nickName'];
        $avatarUrl = $wax_user_info['avatarUrl'];
        $gender = $wax_user_info['gender'];

        $user_we_chat = MemberWeChat::where("unionid", $unionid)
            ->field("member_id")
            ->find();
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $name = $nickName;
        $avatar = $avatarUrl;
        $sex_str = $gender;
        $sex = intval($sex_str);
        if ($sex != 1 && $sex != 2) {
            $sex = $sex_str == "男" ? 1 : ($sex_str == "女" ? 2 : 0);
        }
        $member_id = $user_we_chat->member_id;
        Member::where("member_id", $member_id)->update([
            "name" => $name,
            "avatar" => $avatar,
            "sex" => $sex,
        ]);
        return ResultVo::success();

    }


    /**
     * 微信公众号更新用户信息
     */
    public function updateByWeChatWap()
    {

        $code = request()->post("code");
        // 获取小程序登录信息
        $wap_user_info = WeChatWap::getInstance(WeChat::CONF_NAME_MP)->getUserInfo($code);
        $infoKey = 'openid'; // 取值为：openid unionid
        if ($wap_user_info === false || empty($wap_user_info[$infoKey])) {
            return ResultVo::error(ErrorCode::DATA_NOT, "获取用户信息失败");
        }

        $unionid = $wap_user_info[$infoKey];
        $nickName = $wap_user_info['nickname'];
        $avatarUrl = $wap_user_info['headimgurl'];
        $gender = $wap_user_info['sex'];

        $user_we_chat = MemberWeChat::where("unionid", $unionid)
            ->field("member_id")
            ->find();
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $name = $nickName;
        $avatar = $avatarUrl;
        $sex_str = $gender;
        $sex = intval($sex_str);
        if ($sex != 1 && $sex != 2) {
            $sex = $sex_str == "男" ? 1 : ($sex_str == "女" ? 2 : 0);
        }
        $member_id = $user_we_chat->member_id;
        Member::where("member_id", $member_id)->update([
            "name" => $name,
            "avatar" => $avatar,
            "sex" => $sex,
        ]);
        return ResultVo::success();
    }
}