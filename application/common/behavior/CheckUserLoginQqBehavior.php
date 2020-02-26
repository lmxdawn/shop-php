<?php


namespace app\common\behavior;

use app\common\enums\ErrorCode;
use app\common\exception\JsonException;
use app\common\model\member\MemberQq;
use app\common\utils\UrlUtils;
use think\Request;

/**
 * QQ 第三方登录行为验证
 */
class CheckUserLoginQqBehavior
{
    public function run(Request $request)
    {
        // 行为逻辑
        $openid = request()->post("openid");
        if (strlen($openid) > 32) {
            throw new JsonException(ErrorCode::NOT_NETWORK, "登录信息错误，请重新授权~");
        }
        $openkey = request()->post("openkey");
        $pf = request()->post("pf");
        $appid = MemberQq::APP_ID;
        $format = "json";
        // 签名
        $values = [];
        $values['openid'] = $openid;
        $values['openkey'] = $openkey;
        $values['pf'] = $pf;
        $values['appid'] = $appid;
        $values['format'] = $format;
        $url = "http://openapi.sparta.html5.qq.com";
        $path = "/v3/user/is_login";
        $sig = MemberQq::make($path, $values);
        $values['sig'] = $sig;
        $result = UrlUtils::http($url . $path, $values);
        $result_arr = json_decode($result, true);
        if (!isset($result_arr["ret"]) || $result_arr["ret"] != 0) {
            throw new JsonException(ErrorCode::NOT_NETWORK, $result_arr["msg"]);
        }
        return $openid;
    }

}