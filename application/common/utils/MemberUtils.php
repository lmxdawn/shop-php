<?php


namespace app\common\utils;


use app\common\constant\CacheKeyConstant;

class MemberUtils
{
    /**
     * 验证用户信息
     * @return array|bool
     * @throws \app\common\exception\JsonException
     */
    public static function checkToken()
    {
        // 行为逻辑
        $member_id = request()->param('MEMBER_ID');
        $member_id = $member_id ? $member_id : request()->cookie("MEMBER_ID");
        $token = request()->param('TOKEN');
        $token = $token ? $token : request()->cookie("TOKEN");

        if (!$member_id || !$token) {
            return false;
        }

        $redis = RedisUtils::init();
        if (!$redis) {
            return false;
        }
        $app_name = request()->param("app_name");
        $key = CacheKeyConstant::MEMBER_LOGIN_KEY . $app_name . $member_id;
        $ttl = $redis->ttl($key);
        if ($ttl > 0 && $ttl <= 1 * 86400) {
            $redis->expire($key, 3 * 86400); // 设置过期时间
        }
        $login_info = $redis->hGetAll($key);
        if (empty($login_info["token"]) || $token != $login_info["token"]) {
            return false;
        }
        $login_info["member_id"] = intval($member_id);
        $login_info["token"] = $token;
        return $login_info;
    }

    /**
     * 保存登录信息到Redis
     */
    public static function setLoginRedis($member_id, $value)
    {
        $redis = RedisUtils::init();
        if (!$redis || !is_array($value)) {
            return false;
        }
        $app_name = request()->param("app_name");
        $key = CacheKeyConstant::MEMBER_LOGIN_KEY . $app_name . $member_id;
        $login_info = $redis->hGetAll($key);
        $token = !empty($login_info["token"]) ? $login_info["token"] : "";
        if (empty($token)) {
            $token = TokenUtils::create("u_" . $member_id);
            $value["token"] = $token;
            if (!$redis->hMset($key, $value)) {
                return false;
            }
            $redis->expire($key, 3 * 86400); // 设置过期时间
        }
        if (!empty($value["mp_openid"]) && empty($login_info["mp_openid"])) {
            $redis->hSet($key, "mp_openid", $value["mp_openid"]);
        }
        return $token;
    }

}