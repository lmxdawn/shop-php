<?php


namespace app\api\controller\order;


use app\api\controller\CheckLoginController;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\order\OrderCart;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\Db;

class CartController extends CheckLoginController
{

    public function index() {

        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;
        $list = OrderCart::where("member_id", $this->member_id)
            ->limit($offset, $limit)
            ->select();
        $good_ids = [];
        $list_map = [];
        foreach ($list as $v) {
            $good_ids[] = $v->good_id;
            $list_map[$v->good_id] = $v;
        }

        $good_list = Good::where("good_id", "in", $good_ids)->select();
        $cart_list = [];
        foreach ($good_list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $item = $list_map[$v->good_id];
            $v->count = $item["count"];
            $v->money = bcmul($v->count, $v->shop_price, 2);
            $v->is_check = $item->is_check;
            $cart_list[] = $v;
        }

        return ResultVo::success($cart_list);
    }

    /**
     * 加入购物车
     */
    public function save() {
        $good_id = request()->post("good_id/d", 0);
        $count = request()->post("count/d");
        $type = request()->post("type/d", 0);

        $good = Good::where("good_id", $good_id)->value("good_id");
        if (!$good) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }

        $where = [];
        $where[] = ["member_id", "=", $this->member_id];
        $where[] = ["good_id", "=", $good_id];
        $orderCart = OrderCart::where($where)->find();
        if ($count <= 0) {
            OrderCart::where($where)->delete();
            return ResultVo::success();
        }

        $date_time = date("Y-m-d H:i:s");
        if ($orderCart) {
            $up_data = [];
            $up_data["count"] = $type == 1 ? Db::raw("count + $count") : $count;
            $up_data["update_time"] = $date_time;
            OrderCart::where($where)
                ->update($up_data);
        } else {
            $OrderCart = new OrderCart();
            $OrderCart->insert([
                "member_id" => $this->member_id,
                "good_id" => $good_id,
                "count" => $count,
                "is_check" => 1,
                "create_time" => $date_time,
                "update_time" => $date_time,
            ]);
        }

        return ResultVo::success();
    }

    /**
     * 购物车删除
     */
    public function delete() {
        $good_ids = request()->post("good_ids");

        if (empty($good_ids) || !is_array($good_ids)) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $where = [];
        $where[] = ["member_id", "=", $this->member_id];
        $where[] = ["good_id", "in", $good_ids];
        OrderCart::where($where)->delete();

        return ResultVo::success();
    }

    /**
     * 购物选中
     */
    public function check() {
        $good_ids = request()->post("good_ids");
        $is_check = request()->post("is_check/d");


        $where = [];
        $where[] = ["member_id", "=", $this->member_id];
        $where[] = ["good_id", "in", $good_ids];
        OrderCart::where($where)->update([
            "is_check" => $is_check ? 1 : 0,
            "update_time" => date("Y-m-d H:i:s"),
        ]);

        return ResultVo::success();
    }
}