<?php


namespace app\common\behavior;

use app\common\enums\ErrorCode;
use app\common\exception\JsonException;
use app\common\utils\MemberUtils;

use think\Request;

class CheckUserLoginBehavior
{
    /**
     * @param Request $request
     * @param $params
     * @return array|bool
     * @throws JsonException
     */
    public function run(Request $request)
    {
        // 行为逻辑
        $login_info = MemberUtils::checkToken();
        if (empty($login_info["member_id"])) {
            throw new JsonException(ErrorCode::LOGIN_FAILED);
        }
        return $login_info;
    }

}