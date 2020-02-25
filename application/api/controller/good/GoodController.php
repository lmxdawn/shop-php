<?php
namespace app\api\controller\good;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\good\GoodCategoryList;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

class GoodController
{
    public function index()
    {
        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $category_id = request()->get('category_id', '');
        $key = request()->get('key', '');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;

        $where = [];
        if ($category_id !== "") {
            $good_category_list = GoodCategoryList::where("category_id", intval($category_id))
                ->field("good_id")
                ->order("good_id DESC")
                ->limit($offset, $limit)
                ->select();

            $good_ids = [];
            foreach ($good_category_list as $v) {
                $good_ids[] = $v->good_id;
            }
            $good_ids = array_unique($good_ids);
            $where[] = ['good_id', 'in', $good_ids];
        }
        if ($key !== "") {
            $where[] = ['good_name', 'like', "%$key%"];
        }

        // 新品商品
        $list = Good::where($where)
            ->order("good_id DESC")
            ->limit($offset, $limit)
            ->select();
        foreach ($list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $v->sales_sum = $v->virtual_sales_sum + $v->sales_sum;
            unset($v->virtual_sales_sum);
        }
        return ResultVo::success($list);

    }

    /**
     * 推荐商品
     */
    public function recommend() {
        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;
        // 新品商品
        $list = Good::where("is_recommend", 1)
            ->order("recommend_sort DESC,create_time DESC")
            ->limit($offset, $limit)
            ->select();
        foreach ($list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $v->sales_sum = $v->virtual_sales_sum + $v->sales_sum;
            unset($v->virtual_sales_sum);
        }
        return ResultVo::success($list);
    }

    /**
     * 热门商品
     */
    public function hot() {
        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;
        // 新品商品
        $list = Good::where("is_hot", 1)
            ->order("hot_sort DESC,create_time DESC")
            ->limit($offset, $limit)
            ->select();
        foreach ($list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $v->sales_sum = $v->virtual_sales_sum + $v->sales_sum;
            unset($v->virtual_sales_sum);
        }
        return ResultVo::success($list);
    }
}
