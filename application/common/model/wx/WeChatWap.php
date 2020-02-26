<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/8/17
 * Time: 13:37
 */

namespace app\common\model\wx;

/*
 * 微信公众号
 */

use app\common\utils\FileUtils;
use think\facade\Log;

class WeChatWap extends WeChat
{

    /**
     * 对象实例
     * @var object
     */
    static protected $instances = [];


    private function __clone(){}

    /**
     * @param $from
     * @return WeChatWap
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
     * 获取授权 info
     */
    public function getOauthInfo($code)
    {
        if (!$code) {
            return false;
        }
        $urlObj['appid'] = $this->appid;
        $urlObj['secret'] = $this->appsecret;
        $urlObj['code'] = $code;
        $urlObj['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $res = self::http($url, $urlObj);
        $data = json_decode($res, true);
        if (empty($data["access_token"])) {
            Log::error("获取授权token失败：" . $res);
            return false;
        }
        return $data;
    }

    /**
     * 获取用户信息
     * @return bool|mixed
     */
    public function getUserInfo($code)
    {
        $resData = $this->getOauthInfo($code);
        if (empty($resData['openid']) || empty($resData['access_token'])) {
            return false;
        }
        $urlObj['access_token'] = $resData['access_token'];
        $urlObj['openid'] = $resData['openid'];
        $urlObj['lang'] = "zh_CN";
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $res = self::http($url, $urlObj);
        $data = json_decode($res, true);
        if (empty($data["openid"])) {
            Log::error("获取用户信息失败：" . $res);
            return false;
        }
        return $data;
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
     * 创建场景二维码
     */
    public function qrCodeCreate($action_name, $scene_id, $expire_seconds)
    {
        if (is_int($scene_id)) {
            $scene["scene_id"] = $scene_id;
        } else {
            $scene["scene_str"] = $scene_id;
        }
        $action_info["scene"] = $scene;

        $urlObj["expire_seconds"] = $expire_seconds;
        $urlObj["action_name"] = $action_name;
        $urlObj["action_info"] = $action_info;
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $this->getAccessToken();
        $res = self::http($url, json_encode($urlObj), "POST");
        $data = json_decode($res, true);
        if (empty($data["ticket"])) {
            return null;
        }

        return $data;
    }

    /**
     * 获取base64的二维码
     */
    public function getQRCodeBase($action_name, $scene_id, $expire_seconds = 2592000)
    {
        $data = $this->qrCodeCreate($action_name, $scene_id, $expire_seconds);
        if ($data === null) {
            return null;
        }

        $show_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . urlencode($data["ticket"]);
        $img = imagecreatefromstring(file_get_contents($show_url));
        if (!$img) {
            return null;
        }
        $code = imagecreatetruecolor(100, 100);
        imagecopyresampled($code, $img, 0, 0, 0, 0,100, 100,imagesx($img), imagesy($img));

        $base_path = FileUtils::getUploadsPath();
        $file_path = $base_path . "wx_qr_code/" . $scene_id . ".jpg";

        if (!FileUtils::checkPath(dirname($file_path))) {
            return null;
        }

        imagejpeg($code, $file_path, 50);

        // return $file_path;

        $base64 = "" . chunk_split(base64_encode(file_get_contents($file_path)));
        try{
            unlink($file_path);
        }catch (\Exception $exception){}
        return $base64;
    }


    /**
     * 分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * 上传素材
     */
    public function uploadMedia($type, $filepath)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";

        $data["media"] = new \CURLFile($filepath);
        $res_data = self::http($url, $data, "POST");

        try{
            unlink($filepath);
        }catch (\Exception $exception){}
        $json = json_decode($res_data);
        if (!empty($json->media_id)) {
            return $json->media_id;
        }
        return false;
    }

    /**
     * 获取临时素材
     */
    public function getMedia($media_id, $type = "image")
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}";
        if ($type != "video") {
            return $url;
        }
        $res_data = self::http($url);
        $json = json_decode($res_data);
        if (!empty($json->video_url)) {
            return $json->video_url;
        }
        return false;
    }


    /**
     * 创建菜单
     */
    public function createMenu($json) {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->getAccessToken();
        $res = self::http($url, $json, "POST");
        return $res;
    }

    /**
     * 创建个性菜单
     */
    public function createConditionalMenu($json) {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=" . $this->getAccessToken();
        $res = self::http($url, $json, "POST");
        return $res;
    }

    /**
     * 创建标签
     */
    public function createTag($json)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=" . $this->getAccessToken();
        $res = self::http($url, $json, "POST");
        return $res;
    }

    /**
     * 获取标签列表
     */
    public function getTag()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=" . $this->getAccessToken();
        $res = self::http($url);
        return $res;
    }

    /*
     * 给用户打标签
     */
    public function tagsMembersBatChTagging($openid_list, $tagid)
    {
        $data = [];
        $data["openid_list"] = $openid_list;
        $data["tagid"] = $tagid;
        $json = json_encode($data);
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=" . $this->getAccessToken();
        $res = self::http($url, $json, "POST");
        return $res;
    }

    /**
     * 根据openid获取标签
     */
    public function tagsGetListByOpenid($openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=" . $this->getAccessToken();
        $json = json_encode(["openid" => $openid]);
        $res = self::http($url, $json, "POST");
        return $res;
    }

    /**
     * 发送模板消息
     */
    public function sendTemplateMsg($openid, $template_id, $data, $url = null, $appid = null, $pagepath = null)
    {
        $post = [];
        $post["touser"] = $openid;
        $post["template_id"] = $template_id;
        if ($url) {
            $post["url"] = $url;
        }
        if ($appid && $pagepath) {
            $post["miniprogram"]["appid"] = $appid;
            $post["miniprogram"]["pagepath"] = $pagepath;
        }
        $post["data"] = $data;
        $json = json_encode($post);
        $post_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->getAccessToken();
        $res = self::http($post_url, $json, "POST");
        return $res;
    }

}