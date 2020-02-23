<?php
namespace app\api\controller\other;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\model\ad\Ad;
use app\common\model\good\Good;
use app\common\model\good\GoodRecommend;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

class IndexController
{

    public function index()
    {

        // banner
        $site_key = "index_top_banner";
        $banner_res = Ad::listBySiteKey($site_key);
        $banner_list = $banner_res["list"];
        // 分类信息
        $site_key = "index_cate_list";
        $cate_res = Ad::listBySiteKey($site_key);
        $cate_list = $cate_res["list"];

        // 推荐商品
        $good_recommend = GoodRecommend::where([])
            ->field("good_id")
            ->order("sort DESC,update_time DESC")
            ->limit(4)
            ->select();
        $good_ids = $good_recommend ? array_column($good_recommend->toArray(), "good_id") : [];
        $good_recommend_list = [];
        if ($good_ids) {
            $good_recommend_list = Good::where("good_id", "in", $good_ids)->select();
            foreach ($good_recommend_list as $v) {
                $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            }
        }

        $res = [];
        $res["banner_list"] = $banner_list;
        $res["cate_list"] = $cate_list;
        $res["good_recommend_list"] = $good_recommend_list;
        return ResultVo::success($res);

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
