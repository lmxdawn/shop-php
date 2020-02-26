<?php


namespace app\api\controller\wx;


use app\common\model\wx\WeChatApplet;
use app\common\model\wx\WeChatWap;
use app\common\vo\ResultVo;

class JsSdkController
{
    /**
     * 获取js-sdk 的参数
     */
    public function jsapiTicket()
    {

        $url = request()->get("url");
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        $wx = WeChatApplet::getInstance(WeChatWap::CONF_NAME_MINIAPP);
        // return;

        $nonceStr = substr(str_shuffle($str),26,10);
        $jsapi_ticket = $wx->getTicket("jsapi");
        $timestamp = time();
        $data = [];
        $data[] = "jsapi_ticket=" . $jsapi_ticket;
        $data[] = "noncestr=" . $nonceStr;
        $data[] = "timestamp=" . $timestamp;
        $data[] = "url=" . $url;
        sort($data);
        $string = implode("&", $data);
        $signature = sha1($string);

        $res = [];
        $res["appId"] = $wx->appid;
        $res["timestamp"] = $timestamp;
        $res["nonceStr"] = $nonceStr;
        $res["signature"] = $signature;
        return ResultVo::success($res);
    }


}