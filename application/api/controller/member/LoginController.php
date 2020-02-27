<?php


namespace app\api\controller\member;


use app\api\service\MemberLoginService;
use app\api\service\MemberService;
use app\common\enums\ErrorCode;
use app\common\model\member\MemberWeChat;
use app\common\model\wx\WeChat;
use app\common\model\wx\WeChatApplet;
use app\common\model\wx\WeChatWap;
use app\common\utils\MemberUtils;
use app\common\vo\ResultVo;
use think\Db;
use think\facade\Hook;

class LoginController
{
    /*
     * 微信 app 登录
     */
    public function byWeChatApp()
    {
        $unionid = Hook::exec('app\\common\\behavior\\CheckUserLoginWeChatBehavior', []);
        $user_we_chat = MemberWeChat::where("unionid", $unionid)
            ->field("member_id")
            ->find();
        // 未绑定用户，创建新用户
        if (empty($user_we_chat->member_id)) {
            $data = request()->post();
            // 未查询到信息，创建
            if (!$user_we_chat) {
                $user_we_chat = new MemberWeChat();
                $user_we_chat->unionid = $unionid;
                $user_we_chat->create_time = date("Y-m-d H:i:s");
            }
            // 创建新用户
            if (!MemberService::createUser($data, $user_we_chat)) {
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $member_id = $user_we_chat->member_id;
        $device = request()->cookie("device");
        $token = MemberUtils::setLoginRedis($member_id, ["device" => $device]);
        $res_data = [];
        $res_data["member_id"] = $member_id;
        $res_data["token"] = $token;
        return ResultVo::success($res_data);
    }

    /*
     * 微信小程序登录
     */
    public function byWeChatApplet()
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
        // 启动事务
        Db::startTrans();
        try {
            $res_data = MemberLoginService::weChatApplet($wax_user_info, $infoKey);
            if (empty($res_data["member_id"])) {
                Db::rollback();
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($res_data);

    }


    /**
     * 微信公众号登录
     */
    public function byWeChatWap()
    {

        $code = request()->post("code");
        // 获取小程序登录信息
        $wap_user_info = WeChatWap::getInstance(WeChat::CONF_NAME_MP)->getUserInfo($code);

        $infoKey = 'openid'; // 取值为：openid unionid
        if ($wap_user_info === false || empty($wap_user_info[$infoKey])) {
            return ResultVo::error(ErrorCode::DATA_NOT, "获取用户信息失败");
        }

        // 启动事务
        Db::startTrans();
        try {
            $res_data = MemberLoginService::weChatMp($wap_user_info, $infoKey);
            if (empty($res_data["member_id"])) {
                Db::rollback();
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($res_data);
    }
}