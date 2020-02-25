<?php

namespace app\common\model\ad;

use app\common\utils\PublicFileUtils;
use think\Model;

/**
 * 广告表模型
 */
class Ad extends Model
{

    protected $pk = 'ad_id';

    /**
     * 根据广告位索引获取广告
     * @param $site_key
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function listBySiteKey($site_key) {
        $app_name = request()->cookie("app_name");
        $app_version = request()->cookie("app_version");
        $channel = request()->cookie("channel");
        $user_create_time = request()->get("user_create_time");

        // 判断用户是否是新用户
        $create_time = $user_create_time ? strtotime($user_create_time) : time();
        // 如果是注册不满了36小时的用户，则是新用户
        $user_type = $create_time && $create_time > 0 && (time() - $create_time < 36 * 3600) ? 1 : 0;

        $ad_site = Member::where('site_key',$site_key)
            ->field('site_key,site_name,describe,ad_ids')
            ->find();
        $res = [];
        $res["site_name"] = !empty($ad_site->site_name) ? $ad_site->site_name : "";
        $res["describe"] = !empty($ad_site->describe) ? $ad_site->describe : "";
        $res["list"] = [];
        if (!$ad_site || empty($ad_site->ad_ids) || !is_array(explode(",", $ad_site->ad_ids))) {
            return $res;
        }
        $ad_ids = explode(",", $ad_site->ad_ids);
        foreach ($ad_ids as $k=>$v) {
            $ad_ids[$k] = intval($v);
        }
        $ad_ids = array_unique(array_filter($ad_ids));
        if (empty($ad_ids)) {
            return $res;
        }

        $ad_lists = Ad::whereIn("ad_id", $ad_ids)
            ->where("status", 1)
            ->field("ad_id,title,describe,pic,jump_type,jump_url,ios_url,android_url,wxa_appid,
                channel_type,channel_list,android_version_type,android_version_list,ios_version_type,ios_version_list,
                new_show_start_num,new_show_max_num,old_show_start_num,old_show_max_num,
                start_time,end_time,event_name")
            ->limit(100)
            ->select();
        $lists = [];
        $now_time = time();
        foreach ($ad_lists as $v) {

            if (!empty($v["start_time"]) && strtotime($v["start_time"]) > $now_time) {
                continue;
            }
            if (!empty($v["end_time"]) && strtotime($v["end_time"]) < $now_time) {
                continue;
            }

            // 渠道
            if ($channel && !empty($v["channel_type"]) && !empty($v["channel_list"]) && is_array(explode(",", $v["channel_list"]))) {
                $is_channel_in = $v["channel_type"] == 1 ? true : false;
                $channel_list = explode(",", $v["channel_list"]);
                if ($is_channel_in) {
                    // 如果是白名单，则不存在就跳出循环
                    if (!in_array($channel, $channel_list)) {
                        continue;
                    }
                } else {
                    // 如果是黑名单，则存在就跳出循环
                    if (in_array($channel, $channel_list)) {
                        continue;
                    }
                }
            }

            if ($app_name == "android") {
                // Android 版本
                if (!empty($v["android_version_type"]) && !empty($v["android_version_list"]) && is_array(explode(",", $v["android_version_list"]))) {
                    $is_android_version_in = $v["android_version_type"] == 1 ? true : false;
                    $android_version_list = explode(",", $v["android_version_list"]);
                    if ($is_android_version_in) {
                        // 如果是白名单，则不存在就跳出循环
                        if (!in_array($app_version, $android_version_list)) {
                            continue;
                        }
                    } else {
                        // 如果是黑名单，则存在就跳出循环
                        if (in_array($app_version, $android_version_list)) {
                            continue;
                        }
                    }
                }
            } else if ($app_name == "ios") {
                // iOS 版本
                if (!empty($v["ios_version_type"]) && !empty($v["ios_version_list"]) && is_array(explode(",", $v["ios_version_list"]))) {
                    $is_ios_version_in = $v["ios_version_type"] == 1 ? true : false;
                    $ios_version_list = explode(",", $v["ios_version_list"]);
                    if ($is_ios_version_in) {
                        // 如果是白名单，则不存在就跳出循环
                        if (!in_array($app_version, $ios_version_list)) {
                            continue;
                        }
                    } else {
                        // 如果是黑名单，则存在就跳出循环
                        if (in_array($app_version, $ios_version_list)) {
                            continue;
                        }
                    }
                }
            }


            // 新老用户的逻辑
            if ($user_type == 1) {
                $show_start_num = $v["new_show_start_num"];
                $show_max_num = $v["new_show_max_num"];
            } else {
                $show_start_num = $v["old_show_start_num"];
                $show_max_num = $v["old_show_max_num"];
            }

            $temp = [];
            $temp["ad_id"] = $v["ad_id"];
            $temp["title"] = $v["title"];
            $temp["describe"] = $v["describe"];
            $temp["pic"] = PublicFileUtils::createUploadUrl($v["pic"]);
            $temp["jump_type"] = $v["jump_type"];
            $temp["jump_url"] = $v["jump_url"];
            $temp["ios_url"] = $v["ios_url"];
            $temp["android_url"] = $v["android_url"];
            $temp["wxa_appid"] = $v["wxa_appid"];
            $temp["show_start_num"] = $show_start_num;
            $temp["show_max_num"] = $show_max_num;
            $temp["event_name"] = $v["event_name"];

            $lists[] = $temp;
        }
        $res["list"] = $lists;
        return $res;
    }

}