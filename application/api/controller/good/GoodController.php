<?php
namespace app\api\controller\good;

use app\api\service\MemberService;
use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\good\GoodAttrList;
use app\common\model\good\GoodCategoryList;
use app\common\model\good\GoodComment;
use app\common\model\good\GoodSku;
use app\common\model\good\GoodSpecList;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

class GoodController
{
    public function index()
    {
        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $category_id = request()->get('category_id', '');
        $is_hot = request()->get('is_hot', '');
        $key = request()->get('key', '');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;
        $order = "good_id DESC";
        $where = [];
        if ($key !== "") {
            $where[] = ['good_name', 'like', "%$key%"];
        }
        if ($category_id !== "") {
            if ($category_id == -1) {
                $where = [];
                $where[] = ['is_hot', '=', 1];
                $order = "hot_sort DESC,create_time DESC";
            } else {
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
                $where = [];
                $where[] = ['good_id', 'in', $good_ids];
            }
        }

        // 新品商品
        $list = Good::where($where)
            ->order($order)
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
     * 详情
     */
    public function detail() {

        $good_id = request()->get('good_id/d');
        $info = Good::where("good_id", $good_id)
            ->find();
        if (!$info) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }

        $imgs = explode(",", $info->imgs);
        $temp = [];
        foreach ($imgs as $pic_key => $pic) {
            $temp[] = PublicFileUtils::createUploadUrl($pic);
        }
        $info->imgs = $imgs;
        $info->imgs_url = $temp;
        $info->original_img_url = PublicFileUtils::createUploadUrl($info->original_img);
        $info->sales_sum = $info->virtual_sales_sum + $info->sales_sum;
        unset($info->virtual_sales_sum);


        // 商品评价
        $good_comment = GoodComment::where("good_id", $good_id)
            ->limit(5)
            ->order("create_time DESC")
            ->select();

        if ($good_comment) {
            $member_ids = [];
            foreach ($good_comment as $v) {
                $member_ids[] = $v->member_id;
            }
            $member_map = MemberService::listMemberInfoByMemberIdIn($member_ids);
            foreach ($good_comment as $v) {
                $v->member = $member_map[$v->member_id] ?? [];
            }
        }

        $info->good_comment = $good_comment;

        return ResultVo::success($info);


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
