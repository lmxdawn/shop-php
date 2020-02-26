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
use app\common\constant\WeChatCustomMsgConstant;
use app\common\model\user\User;
use app\common\model\user\UserClassmate;
use app\common\model\user\UserInviteRecord;
use app\common\model\user\UserMp;
use app\common\model\user\UserProfile;
use app\common\service\WeChatService;
use app\common\utils\PublicFileUtils;
use app\common\utils\UserUtils;
use think\Db;
use think\facade\Log;

/**
 * 微信自动回复
 */
class WeChatInterfaceMsg extends WeChat
{

    private $MsgType;
    private $ToUserName;
    private $FromUserName;
    private $Event;
    private $json;

    private $sendData;

    /**
     * 对象实例
     * @var object
     */
    static protected $instances = [];


    private function __clone(){}

    /**
     * @param $from
     * @return WeChatInterfaceMsg
     * @throws \app\common\exception\JsonException
     */
    public static function getInstance($from)
    {
        if (empty(self::$instances[$from]) || !self::$instances[$from] instanceof self) {
            self::$instances[$from] = new self($from);
        }
        return self::$instances[$from];
    }

    public function init()
    {
        $postStr = file_get_contents("php://input");
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (empty($postObj)) {
            exit;
        }
        $postArray = (array)$postObj;
        $arr = [];
        foreach ($postArray as $k => $v) {
            $arr[$k] = trim($v);
        }
        $this->json = $arr;
        if (false != $this->json) {
            $this->MsgType = isset($this->json["MsgType"]) ? $this->json["MsgType"] : '';
            $this->Event = isset($this->json["Event"]) ? $this->json["Event"] : '';
            $this->ToUserName = isset($this->json["ToUserName"]) ? $this->json["ToUserName"] : ''; // 	小程序的原始ID
            $this->FromUserName = isset($this->json["FromUserName"]) ? $this->json["FromUserName"] : ''; // 发送者的 openID
            try {
                switch ($this->MsgType) {
                    case 'event':
                        $this->event();
                        break;
                    case 'text':
                        $this->textMsg();
                        break;
                    default:
                        break;

                }
                // 更新操作时间
                $this->upModifiedTime();
            } catch (\Exception $exception) {

            }
        }
    }

    /**
     * 文本消息
     */
    public function textMsg()
    {
        if (empty($this->json["Content"])) {
            return "";
        }
        switch ($this->json["Content"]) {
            case '识别码':
                break;
            default:
                break;
        }
    }

    /**
     * event 类型的消息
     */
    private function event()
    {
        switch ($this->Event) {
            case 'subscribe': // 用户未关注时的关注
                $this->subscribe();
                break;
            case 'unsubscribe': // 取消关注公众号
                $this->unsubscribe();
                break;
            case 'SCAN': // 扫码用户已关注公众号
                $this->SCAN();
                break;
            case 'CLICK': // 扫码用户已关注公众号
                $this->click();
                break;
            default:
                break;
        }
    }

    /**
     * 自定义点击事件
     */
    public function click()
    {
        $key = "";
        if (!empty($this->json["EventKey"])) {
            $key = $this->json["EventKey"];
        }

        $click = new WeChatInterfaceClick();

        switch ($key) {
            case 'V1001_XUE_BA': // 学霸勋章
                $click->badge($this->FromUserName, $this->ToUserName, 1);
                break;
            case 'V1001_JIAN_CHI': // 坚持勋章
                $click->badge($this->FromUserName, $this->ToUserName, 2);
                break;
            default:
                break;
        }

    }


    /**
     * 关注公众号
     */
    public function subscribe()
    {
        $invite_user_id = 0;
        $mianduimian_user_id = 0; // 面对面邀请者的id
        if (!empty($this->json["EventKey"])) {
            if (false !== strpos($this->json["EventKey"], "qrscene_c")) {
                $mianduimian_user_id = intval(str_replace("qrscene_c", "", $this->json["EventKey"]));
            } else {
                $invite_user_id = intval(str_replace("qrscene_", "", $this->json["EventKey"]));
            }
        }

        // 创建用户
        $user_data = $this->createUser($invite_user_id);

        $user_id = $user_data["user_id"];
        $is_new = !empty($user_data["is_new"]) ? 1 : 0;

        $user_profile = UserProfile::where("user_id", $user_id)
            ->field("study_id")
            ->find();

        if (!empty($user_profile["study_id"])) {
            WeChatService::tagsMembersBatTaggingByStudy($user_id);
        }

        $openid = $this->FromUserName;
        // 如果不是面对面邀请
        if ($mianduimian_user_id == 0) {
            $url = PublicFileUtils::getH5Domain() . "/study/join?show_type=1&ad=wechat";
            $content = sprintf(WeChatCustomMsgConstant::SUBSCRIBE_MSG, $url);

            // 如果是新用户，并且是特殊二维码扫码进来的
            if ($is_new && $invite_user_id == UserUtils::specialUserId()) {
                $content = WeChatCustomMsgConstant::SHIYONG_SUBSCRIBE_MSG;
            }
        } else {

            // 如果是特殊邀请者
            if ($mianduimian_user_id == UserUtils::specialUserId()) {
                $url = PublicFileUtils::getH5Domain() . "/invite";
                $content = sprintf(WeChatCustomMsgConstant::DISANFANG_SUBSCRIBE_MSG, $url);
            } else {
                $url = PublicFileUtils::getH5Domain() . "/study/join?invite_user_id=" . $mianduimian_user_id;
                $user_name = User::where("user_id", $mianduimian_user_id)->value("user_name");
                $content = sprintf(WeChatCustomMsgConstant::MIANDUIMIAN_SUBSCRIBE_MSG, $user_name, $url);
            }

        }

        WeChatService::sendCustomMsgText($user_id, $content, $openid);

    }

    /*
     *  取消关注
     */
    public function unsubscribe()
    {
        $openid = $this->FromUserName;

    }


    /**
     * 用户已关注重新扫码事件
     */
    public function SCAN()
    {

        $invite_user_id = 0;
        $mianduimian_user_id = 0; // 面对面邀请者的id
        if (!empty($this->json["EventKey"])) {
            if (false !== strpos($this->json["EventKey"], "c")) {
                $mianduimian_user_id = intval(str_replace("c", "", $this->json["EventKey"]));
            } else {
                $invite_user_id = intval($this->json["EventKey"]);
            }
        }

        // 创建用户
        $user_data = $this->createUser($invite_user_id);

        $user_id = $user_data["user_id"];

        if ($mianduimian_user_id > 0) {
            $openid = $this->FromUserName;
            // 如果是特殊邀请者
            if ($mianduimian_user_id == UserUtils::specialUserId()) {
                $url = PublicFileUtils::getH5Domain() . "/invite";
                $content = sprintf(WeChatCustomMsgConstant::DISANFANG_SUBSCRIBE_MSG, $url);
            } else {
                $url = PublicFileUtils::getH5Domain() . "/study/join?invite_user_id=" . $mianduimian_user_id;
                $user_name = User::where("user_id", $mianduimian_user_id)->value("user_name");
                $content = sprintf(WeChatCustomMsgConstant::MIANDUIMIAN_SUBSCRIBE_MSG, $user_name, $url);
            }

            WeChatService::sendCustomMsgText($user_id, $content, $openid);
        }

    }

    /**
     * 根据openid获取用户信息
     * @return bool|mixed
     */
    public function getUserInfoByOpenID($openid)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $res = self::http($url);
        $data = json_decode($res, true);
        if (!empty($data["openid"])) {
            return $data;
        }
        return false;
    }

    /**
     * 创建用户 （如果存在则返回用户id）
     * @param $invite_user_id
     * @return mixed
     */
    private function createUser($invite_user_id = 0)
    {

        $openid = $this->FromUserName;
        // 获取用户信息
        $wx_user_info = $this->getUserInfoByOpenID($openid);

        if (empty($wx_user_info["unionid"])) {
            $this->sendText("网络繁忙, 请重新扫码");
        }

        $isTrial = $invite_user_id == UserUtils::specialUserId();
        // 启动事务
        Db::startTrans();
        try {
            $res_data = UserLoginService::weChatMp($wx_user_info, $isTrial);
            if (empty($res_data["user_id"])) {
                Db::rollback();
                $this->sendText("网络繁忙, 请重新扫码");
            }
            $user_id = $res_data["user_id"];
            // 如果不是试用的，并且是被人邀请来的，并且是新增的用户
            if (!$isTrial && $invite_user_id > 0 && !empty($res_data["is_new"])) {
                $gold = 1;
                $desc = "互赠金币";
                UserGoldService::inc($invite_user_id, $gold, $desc, $user_id);
                // 给当前用户增加金币
                $to_user_gold = 1;
                UserGoldService::inc($user_id, $to_user_gold, $desc);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->sendText("网络繁忙, 请重新扫码");
            exit;
        }

        // 如果是邀请来的，并且不是试用，并且不是用户本人
        if ($invite_user_id > 0 && !$isTrial && $invite_user_id != $user_id) {
            try {
                // 判断是否是同学
                $temp_user_classmate = UserClassmate::where("user_id", $user_id)
                    ->where("classmate_user_id", $invite_user_id)
                    ->field("classmate_user_id")
                    ->find();
                if (empty($temp_user_classmate)) {
                    // 增加同学列表
                    $user_classmate_data = [];
                    $user_classmate = [];
                    $user_classmate["user_id"] = $user_id;
                    $user_classmate["classmate_user_id"] = $invite_user_id;
                    $user_classmate["create_time"] = date("Y-m-d H:i:s");
                    $user_classmate["modified_time"] = date("Y-m-d H:i:s");
                    $user_classmate_data[] = $user_classmate;
                    $user_classmate["user_id"] = $invite_user_id;
                    $user_classmate["classmate_user_id"] = $user_id;
                    $user_classmate_data[] = $user_classmate;
                    $user_classmate_sql = UserClassmate::fetchSql(true)->insertAll($user_classmate_data);
                    $user_classmate_sql.= " ON DUPLICATE KEY UPDATE modified_time = VALUES(modified_time)";
                    Db::execute($user_classmate_sql);
                }
            } catch (\Exception $e) {}
        }

        return $res_data;
    }

    /**
     * 更新操作时间
     */
    public function upModifiedTime()
    {
        $openid = $this->FromUserName;

        $up_data = [];
        $up_data["modified_time"] = date("Y-m-d H:i:s");
        // 判断是不是取消关注
        if ($this->MsgType == "event" && $this->Event == "unsubscribe") {
            // 修改打标签的状态
            $up_data["is_tag"] = 0;
        }


        UserMp::where("openid", $openid)->update($up_data);
    }

    /**
     * 回复文本消息
     */
    public function sendText($content)
    {
        $this->sendData["MsgType"] = "text";
        $this->sendData["Content"] = $content;
        $this->send();
    }


    /**
     * 发送消息
     */
    public function send()
    {
        $this->sendData["ToUserName"] = $this->FromUserName;
        $this->sendData["FromUserName"] = $this->ToUserName;
        $this->sendData["CreateTime"] = time();
        $xml = $this->ToXml($this->sendData);
        exit($xml);
    }


}
