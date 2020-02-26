<?php


namespace app\common\model\member;


use think\Model;

class MemberQq extends Model
{
    const APP_ID = "1107789192";
    const APP_KEY = "bGkfyvMdkMQBv4yO";

    protected $pk = "openid";


    /**
     * sign 生成
     * @param $path
     * @param array $values 生成的value 值
     * @return mixed
     */
    public static function make($path, $values)
    {
        // 签名步骤一：将请求的URI路径进行URL编码
        $path = rawurlencode($path);
        // 签名步骤二：将除“sig”外的所有参数按key进行字典升序排列
        ksort($values);
        // 签名步骤三：将第2步中排序后的参数(key=value)用&拼接起来
        $string = self::toUrlParams($values);
        // 然后进行URL编码
        $string = rawurlencode($string);
        //签名步骤四：将HTTP请求方式，第1步以及第3步中的到的字符串用&拼接起来，得到源串
        $string = "GET&" . $path . "&" . $string;
        // dump($string);exit;
        // 签名步骤五：在应用的appkey末尾加上一个字节的“&”，即appkey&
        $key = self::APP_KEY . "&";
        // 签名步骤六：生成签名值
        $result = hash_hmac("sha1", $string, $key, true);
        return rawurlencode(base64_encode($result));
    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function toUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

}