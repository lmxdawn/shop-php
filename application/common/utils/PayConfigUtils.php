<?php


namespace app\common\utils;


use app\common\model\wx\WeChatWap;

class PayConfigUtils
{
    /**
     * 微信支付的配置
     * @param $pay_type
     * @param $conf
     * @return \Yansongda\Pay\Gateways\Wechat
     */
    public static function weChatPay($pay_type, $conf = []) {
        $gateway = "";
        switch ($pay_type) {
            case WeChatWap::CONF_NAME_MP: // 微信公众号
                $gateway = "mp";
                break;
            case WeChatWap::CONF_NAME_MINIAPP: // 微信小程序
                $gateway = "miniapp";
                break;
            case WeChatWap::CONF_NAME_WAP: // 微信H5支付
                $gateway = "wap";
                break;
        }
        $wx_pay = config('wx.' . $gateway);
        if ($conf) {
            $wx_pay = array_merge($wx_pay, $conf);
        }

        return \Yansongda\Pay\Pay::wechat($wx_pay);
    }

}