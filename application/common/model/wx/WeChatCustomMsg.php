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

use think\facade\Log;

/**
 * 微信客服消息
 */
class WeChatCustomMsg extends WeChat
{

    private $sendData;


    /**
     * 对象实例
     * @var object
     */
    static protected $instances = [];


    private function __clone(){}

    /**
     * @param $from
     * @return WeChatCustomMsg
     * @throws \app\common\exception\JsonException
     */
    public static function getInstance($from)
    {
        if (empty(self::$instances[$from]) || !self::$instances[$from] instanceof self) {
            self::$instances[$from] = new self($from);
        }
        return self::$instances[$from];
    }

    /**
     * 文本消息
     */
    public function sendText($openid, $content)
    {
        $this->sendData["touser"] = $openid;
        $this->sendData["msgtype"] = "text";
        $this->sendData["text"] = [
            "content" => $content
        ];
        return $this->send();
    }

    /**
     * 图片消息
     */
    public function sendImage($openid, $media_id)
    {
        $this->sendData["touser"] = $openid;
        $this->sendData["msgtype"] = "image";
        $this->sendData["image"] = [
            "media_id" => $media_id
        ];
        return $this->send();
    }


    /**
     * 发送消息
     */
    private function send(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
        // 一定要加 JSON_UNESCAPED_UNICODE，微信那边不转义
        $jsonStr = json_encode($this->sendData, JSON_UNESCAPED_UNICODE);
        $res = self::http($url, $jsonStr, 'POST');
        $data = json_decode($res, true);
        if (!empty($data["errcode"])) {
            Log::error("发送客服消息错误：" . $res . "，发送数据为：" . $jsonStr);
            return false;
        }
        // 调用接口发送小程序客服消息
        return $data;
    }
}
