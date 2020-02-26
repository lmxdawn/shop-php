<?php


namespace app\api\controller;


use think\facade\Hook;

/**
 * 需要用户登录的 controller
 */
class CheckLoginController
{
    protected $member_id;

    protected $token;

    protected $mp_openid;

    public function __construct()
    {
        $login_info = Hook::exec('app\\common\\behavior\\CheckUserLoginBehavior', []);
        $this->member_id = intval($login_info["member_id"]);
        $this->token = $login_info["token"];
        $this->mp_openid = !empty($login_info["mp_openid"]) ? $login_info["mp_openid"] : null;
    }

}