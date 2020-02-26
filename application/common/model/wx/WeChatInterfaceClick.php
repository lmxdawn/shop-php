<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 .
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎明晓 <lmxdawn@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\model\wx;

use app\api\service\UserGoldService;
use app\api\service\UserLoginService;
use app\api\service\UserService;
use app\common\constant\WeChatCustomMsgConstant;
use app\common\model\study\StudyRecord;
use app\common\model\user\User;
use app\common\model\user\UserClassmate;
use app\common\model\user\UserInviteRecord;
use app\common\model\user\UserMp;
use app\common\model\user\UserProfile;
use app\common\model\user\UserWeChat;
use app\common\service\StudyMsgService;
use app\common\service\WeChatService;
use app\common\utils\PublicFileUtils;
use app\common\utils\UserUtils;
use think\Db;
use think\facade\Log;

/**
 * 微信自动回复的点击事件
 */
class WeChatInterfaceClick
{

    private $sendData;

    private $openid;
    private $fromUser;

    /**
     * 勋章的点击
     */
    public function badge($openid, $fromUser, $type) {

        $this->openid = $openid;
        $this->fromUser = $fromUser;

        Log::error("网络出了点小状况：" . $openid);
        $user_mp = UserMp::where("openid", $openid)
            ->field("user_id")
            ->find();
        if (empty($user_mp["user_id"])) {
            $this->sendText("网络出了点小状况~ 请重试哦 😊");
        }

        $user_id = $user_mp["user_id"];

        // 查询当前是否完成学习
        $study_record = StudyRecord::where("user_id", $user_id)
            ->where("create_date", date("Y-m-d"))
            ->field("status")
            ->find();

        if (empty($study_record["status"])) {
            $this->sendText("抱歉，今天您还没有完成学习哦！请先完成今日学习任务再来获取勋章吧！ 😊");
        }

        $user = User::where("user_id", $user_id)
            ->field("user_name,avatar")
            ->find();
        if (empty($user)) {
            Log::error("单词徽章：没有查询到用户信息：" . $user_id);
            return false;
        }
        $user_name = !empty($user["user_name"]) ? $user["user_name"] : "";
        $avatar = !empty($user["avatar"]) ? $user["avatar"] : null;

        $user_profile = UserProfile::where("user_id", $user_id)
            ->field("day_count,word_count")
            ->find();

        if ($type == 1) {
            $word_count = !empty($user_profile["word_count"]) ? $user_profile["word_count"] : 0;

            switch ($word_count){
                case $word_count > 10000:
                    $num = 10000;
                    break;
                case $word_count > 5000:
                    $num = 5000;
                    break;
                case $word_count > 2000:
                    $num = 2000;
                    break;
                case $word_count > 1000:
                    $num = 1000;
                    break;
                case $word_count > 500:
                    $num = 500;
                    break;
                case $word_count > 200:
                    $num = 200;
                    break;
                case $word_count > 100:
                    $num = 100;
                    break;
                case $word_count > 50:
                    $num = 50;
                    break;
                default:
                    $this->sendText("抱歉，您还没有获得过任何勋章哦！请继续学习吧！ 😊");
                    exit;
            }

        } else {
            $day_count = !empty($user_profile["day_count"]) ? $user_profile["day_count"] : 0;
            switch ($day_count){
                case $day_count > 1000:
                    $num = 1000;
                    break;
                case $day_count > 500:
                    $num = 500;
                    break;
                case $day_count > 365:
                    $num = 365;
                    break;
                case $day_count > 200:
                    $num = 200;
                    break;
                case $day_count > 150:
                    $num = 150;
                    break;
                case $day_count > 100:
                    $num = 100;
                    break;
                case $day_count > 60:
                    $num = 60;
                    break;
                case $day_count > 30:
                    $num = 30;
                    break;
                case $day_count > 21:
                    $num = 21;
                    break;
                case $day_count > 14:
                    $num = 14;
                    break;
                case $day_count > 10:
                    $num = 10;
                    break;
                case $day_count > 7:
                    $num = 7;
                    break;
                case $day_count > 5:
                    $num = 5;
                    break;
                case $day_count > 3:
                    $num = 3;
                    break;
                default:
                    $this->sendText("抱歉，您还没有获得过任何勋章哦！请继续学习吧！ 😊");
                    exit;
            }
        }


        // 获取头像
        $avatar_image = UserService::getAvatarBase64($user_id, $avatar);

        // 获取二维码
        $code = UserService::getWxQrCodeBase64($user_id);
        $media_id = StudyMsgService::createWeChatMediaBadge($type, $user_id, $user_name, $avatar_image, $code, $num);

        $this->sendImage($media_id);

    }

    /**
     * 回复文本消息
     */
    private function sendText($content)
    {
        $this->sendData["MsgType"] = "text";
        $this->sendData["Content"] = $content;
        $this->send();
    }


    /**
     * 回复图片消息
     */
    private function sendImage($media_id)
    {
        $this->sendData["MsgType"] = "image";
        $this->sendData["Image"]["MediaId"] = $media_id;
        $this->send();
    }


    /**
     * 发送消息
     */
    private function send()
    {
        $this->sendData["ToUserName"] = $this->openid;
        $this->sendData["FromUserName"] = $this->fromUser;
        $this->sendData["CreateTime"] = time();
        $xml = $this->ToXml($this->sendData);
        exit($xml);
    }


    /**
     * 输出xml字符
     * @param $values
     * @param bool $isRoot
     * @return string
     */
    protected function ToXml($values, $isRoot = true)
    {
        $xml = $isRoot ? "<xml>" : "";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else if (is_array($val)){
                $xml .= "<" . $key . ">" . $this->ToXml($val, false) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        if ($isRoot) {
            $xml .= "</xml>";
        }
        return $xml;
    }

}
