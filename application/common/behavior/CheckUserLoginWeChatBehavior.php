<?php

namespace app\common\behavior;

use app\common\enums\ErrorCode;
use app\common\exception\JsonException;
use app\common\utils\UrlUtils;
use think\Request;

/**
 * 微信 第三方登录行为验证
 */
class CheckUserLoginWeChatBehavior
{
    public function run(Request $request)
    {
        // 行为逻辑
        $access_token = request()->post("access_token");
        $openid = request()->post("openid");
        // 签名
        $values = [];
        $values['access_token'] = $access_token;
        $values['openid'] = $openid;
        $url = "https://api.weixin.qq.com";
        $path = "/sns/userinfo";
        $result = UrlUtils::http($url . $path, $values);
        $result_arr = json_decode($result, true);
        if (isset($result_arr["errcode"]) && $result_arr["errcode"] != 0) {
            throw new JsonException(ErrorCode::NOT_NETWORK, $result_arr["errmsg"]);
        }
        if (empty($result_arr["unionid"])) {
            throw new JsonException(ErrorCode::NOT_NETWORK, "invalid unionid");
        }
        $unionid = $result_arr["unionid"];
        return $unionid;
    }

}