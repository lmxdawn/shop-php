<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/8/17
 * Time: 13:37
 */

namespace app\common\model\wx;

/*
 * 微信小程序
 */

use think\facade\Log;

class WeChatApplet extends WeChat
{

    /**
     * 对象实例
     * @var object
     */
    static protected $instances = [];


    private function __clone(){}

    /**
     * @param $from
     * @return WeChatApplet
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
     * 获取小程序 openID
     */
    public function getOpenid($code)
    {
        if (!$code) {
            return false;
        }
        $urlObj['appid'] = $this->appid;
        $urlObj['secret'] = $this->appsecret;
        $urlObj['js_code'] = $code;
        $urlObj['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $res = self::http($url, $urlObj);
        $data = json_decode($res, true);
        if (!empty($data)) {
            return $data;
        }
        return false;
    }

    /**
     * 获取小程序的登录信息
     * @param $code
     * @param $encryptedData
     * @param $iv
     * @return bool|mixed
     */
    public function getUserInfo($code, $encryptedData, $iv)
    {
        $resData = $this->getOpenid($code);
        if (empty($resData['session_key'])) {
            return false;
        }
        $session_key = $resData['session_key'];
        $errCode = $this->aesDecrypt($session_key, $encryptedData, $iv, $data);
        if (!$errCode) {
            return false;
        }
        return json_decode($data, true);
    }

    /*
     * 获取群id
     */
    public function getOpenGid($code, $encryptedData, $iv)
    {
        $resData = $this->getOpenid($code);
        if (empty($resData['session_key'])) {
            return false;
        }
        $session_key = $resData['session_key'];
        $errCode = $this->aesDecrypt($session_key, $encryptedData, $iv, $data);
        if (!$errCode) {
            return false;
        }
        return json_decode($data, true);
    }

    /**
     * 微信的 AES 解密
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @return bool
     */
    public function aesDecrypt($sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) != 24) {
            return false;
        }
        $aesKey = base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            return false;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $decrypted = openssl_decrypt($aesCipher, 'aes-128-cbc', $aesKey, OPENSSL_RAW_DATA, $aesIV);
        $dataObj = json_decode($decrypted);
        if ($dataObj == NULL) {
            return false;
        }
        $appid = $this->appid;
        if ($dataObj->watermark->appid != $appid) {
            return false;
        }
        $data = $decrypted;
        return true;
    }

    // 获取小程序码
    public function wxaCode($scene, $page, $width = null, $auto_color = true, $line_color = ["r" => 0, "g" => 0, "b" => 0], $is_hyaline = false)
    {
        // 获取 access_token
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $params = [
            'scene' => $scene,
            'page' => $page,
            'width' => $width,
            'auto_color' => $auto_color,
            'line_color' => $line_color,
            'is_hyaline' => $is_hyaline,
        ];
        $res = self::http($url, json_encode($params), 'POST');
        $temp = json_decode($res);
        if (!empty($temp->errcode)) {
            return null;
        }
        $img = imagecreatefromstring($res);
        return $img;
    }

    /**
     * 小程序发送模板消息
     * @param string $touser 接收者的openid
     * @param string $form_id 表单id
     * @param string $template_id 所需下发的模板消息的id
     * @param array $data 模板内容
     * @param string $page 点击模板卡片后的跳转页面
     * @param string $color 模板内容字体的颜色
     * @param string $emphasis_keyword 模板需要放大的关键词
     * @return mixed
     */
    public function templateMsg($touser, $form_id, $template_id, $data = [], $page = '', $color = '', $emphasis_keyword = '')
    {
        $access_token = $this->getAccessToken();
        $templateMsgData = [];
        $templateMsgData['touser'] = $touser;
        $templateMsgData['template_id'] = $template_id;
        $templateMsgData['form_id'] = $form_id;
        $templateMsgData['page'] = $page;
        $templateMsgData['data'] = $data;
        $templateMsgData['color'] = $color;
        $templateMsgData['emphasis_keyword'] = $emphasis_keyword;
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $res = self::http($url, json_encode($templateMsgData), "POST");
        $temp = json_decode($res);
        if (!empty($temp->errcode)) {
            Log::error("发送消息失败：" . $res . "，form_id：" . $form_id . "，template_id" . $template_id);
            return false;
        }
        return true;
    }

}