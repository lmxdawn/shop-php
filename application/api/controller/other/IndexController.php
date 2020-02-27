<?php
namespace app\api\controller\other;

use app\common\model\ad\Ad;
use app\common\model\good\Good;
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

        // 新品商品
        $good_new_list = Good::where("status", 1)
            ->where("is_new", 1)
            ->order("new_sort DESC,create_time DESC")
            ->limit(4)
            ->select();
        foreach ($good_new_list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            unset($v->virtual_sales_sum);
        }

        $res = [];
        $res["banner_list"] = $banner_list;
        $res["cate_list"] = $cate_list;
        $res["good_new_list"] = $good_new_list;
        return ResultVo::success($res);

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
