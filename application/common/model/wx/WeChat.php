<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/8/17
 * Time: 13:37
 */

namespace app\common\model\wx;

/*
 * 微信基础类
 */
use app\common\constant\CacheKeyConstant;
use app\common\enums\ErrorCode;
use app\common\exception\JsonException;
use app\common\utils\RedisUtils;
use app\common\utils\UrlUtils;

abstract class WeChat
{
    public $appid;
    protected $appsecret;
    protected $conf_name; // 配置名称
    protected $from; // 小程序来源
    protected $access_token = []; //
    protected $ticket = []; //

    // 微信小程序
    const CONF_NAME_MINIAPP = 0;
    // 微信公众号
    const CONF_NAME_MP = 1;
    // 微信H5
    const CONF_NAME_WAP = 2;

    const CONF_ARR = [
        0 => "miniapp",
        1 => "mp",
        2 => "wap",
    ];

    /**
     * Wx constructor.
     * @throws JsonException
     */
    protected function __construct($from)
    {
        if (self::CONF_NAME_MINIAPP != $from && self::CONF_NAME_MP != $from) {
            throw new JsonException(1, "微信配置错误");
        }
        $this->from = $from;
        $this->conf_name = self::CONF_ARR[$from];
        $this->appid = config('wx.'. $this->conf_name .'.app_id');
        $this->appsecret = config('wx.'. $this->conf_name .'.appsecret');
    }

    // 网络请求
    public function http($url, $params = [], $method = 'GET', $header = [])
    {
        $res = UrlUtils::http($url, $params, $method, $header);
        return $res;
    }

    /*
     * 获取access_token
     */
    public function getAccessToken()
    {
        if (!empty($this->access_token[$this->appid])) {
            return $this->access_token[$this->appid];
        }

        $redis = RedisUtils::init("redis_wx");
        $key = CacheKeyConstant::WX_ACCESS_TOKEN . $this->conf_name;
        if ($redis->exists($key) && !empty($access_token = $redis->get($key))) {
            return $access_token;
        }
        $grant_type = 'client_credential';
        $token_url = 'https://api.weixin.qq.com/cgi-bin/token';
        $params = [
            'grant_type' => $grant_type,
            'appid' => $this->appid,
            'secret' => $this->appsecret,
        ];
        $content = self::http($token_url, $params);
        $content = json_decode($content,true); // 解码
        if (empty($content['access_token'])) {
            return false;
        }
        $access_token = $content['access_token'];
        if ($redis instanceof \Redis) {
            $redis->set($key, $access_token, 7000);
        }
        $this->access_token[$this->appid] = $access_token;
        return $access_token;
    }

    /*
     * 获取access_token（文件形式保存）
     */
    public function getAccessTokenFile()
    {
        if (!empty($this->access_token[$this->appid])) {
            return $this->access_token[$this->appid];
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . '/accesstoken_' . $this->appid . ".json";
        //判断这个文件是否存在
        if (file_exists($file)) {
            $content = file_get_contents($file); // 获取文件信息
            $content = json_decode($content); // 解码
            if (!empty($content->access_token) && time() - filemtime($file) < $content->expires_in) {
                $this->access_token[$this->appid] = $content->access_token;
                return $content->access_token;
            }
        }
        $grant_type = 'client_credential';
        $token_url = 'https://api.weixin.qq.com/cgi-bin/token';
        $params = ['grant_type' => $grant_type, 'appid' => $this->appid, 'secret' => $this->appsecret,];
        $json = self::http($token_url, $params);
        $content = json_decode($json); // 解码
        if (empty($content->access_token)) {
            throw new JsonException(ErrorCode::DATA_VALIDATE_FAIL);
        }
        file_put_contents($file, $json);
        $this->access_token[$this->appid] = $content->access_token;
        return $content->access_token;
    }

    /*
     * 获取 ticket 票据
     */
    public function getTicket($type)
    {
        $index = $this->conf_name . $type;

        if (!empty($this->ticket[$index])) {
            return $this->ticket[$index];
        }

        $redis = RedisUtils::init("redis_wx");
        $key = CacheKeyConstant::WX_JS_TICKET . $index;
        $content = $redis->get($key);
        $content = json_decode($content); // 解码
        if (!empty($content->ticket) && time() - $content->create_time < $content->expires_in) {
            return $content->ticket;
        }
        $token_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
        $params = [
            'access_token' => $this->getAccessToken(),
            'type' => $type
        ];
        $content = self::http($token_url, $params);
        $content = json_decode($content,true); // 解码
        if (empty($content["ticket"])) {
            return false;
        }
        $content['create_time'] = time();
        if ($redis instanceof \Redis) {
            $redis->set($key,json_encode($content));
        }
        $this->ticket[$index] = $content["ticket"];
        return $content['ticket'];
    }

    /*
     * 获取 ticket 票据（文件方式保存）
     */
    public function getTicketFile($type)
    {
        $index = $this->conf_name . $type;

        if (!empty($this->ticket[$index])) {
            return $this->ticket[$index];
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . '/ticket_' . $index . ".json";
        //判断这个文件是否存在
        if (file_exists($file)) {
            $content = file_get_contents($file); // 获取文件信息
            $content = json_decode($content); // 解码
            if (!empty($content->ticket) && time() - filemtime($file) < $content->expires_in) {
                $this->ticket[$index] = $content->ticket;
                return $content->ticket;
            }
        }


        $token_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
        $params = [
            'access_token' => $this->getAccessToken(),
            'type' => $type
        ];
        $json = self::http($token_url, $params);
        $content = json_decode($json); // 解码
        if (empty($content->ticket)) {
            throw new JsonException(ErrorCode::DATA_VALIDATE_FAIL);
        }
        file_put_contents($file, $json);
        $this->ticket[$index] = $content->ticket;
        return $content->ticket;
    }



    /**
     * 输出xml字符
     * @param $values
     * @return string
     */
    protected function ToXml($values)
    {
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

}