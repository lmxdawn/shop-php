<?php


namespace app\api\controller\order;


use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

class OrderController
{

    /**
     * 下单时获取商品列表
     */
    public function submitGoodList() {

        $good_ids = request()->post("good_ids");
        $counts = request()->post("counts");
        $good_ids = explode(",", $good_ids);
        $counts = explode(",", $counts);
        if (empty($good_ids) || empty($counts) || !is_array($good_ids) || !is_array($counts)) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $good_map = [];
        foreach ($good_ids as $k=>$v) {
            $good_map[$v] = $counts[$k];
        }
        $good_ids = array_unique($good_ids);

        // 新品商品
        $list = Good::where("good_id", "in", $good_ids)
            ->select();
        if (count($list) == 0) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $list_map = [];
        foreach ($list as $v) {
            $count = $good_map[$v->good_id] ?? 0;
            if ($count > $v->store_count) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "部分商品库存不足");
            }
            $v->count = $count;
            $v->money = bcmul($v->shop_price, $count, 2);
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $v->sales_sum = $v->virtual_sales_sum + $v->sales_sum;
            unset($v->virtual_sales_sum);
            $list_map[$v->good_id] = $v;
        }

        $sum_money = 0;
        $good_list = [];
        foreach ($good_ids as $v) {
            if (!empty($list_map[$v])) {
                $item = $list_map[$v];
                $sum_money = bcadd($item->money, $sum_money, 2);
                $good_list[] = $item;
            }
        }

        $res = [];
        $res["good_list"] = $good_list;
        $res["sum_money"] = $sum_money;
        return ResultVo::success($res);
    }

}