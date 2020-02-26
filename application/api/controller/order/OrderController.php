<?php


namespace app\api\controller\order;


use app\api\controller\CheckLoginController;
use app\api\service\OrderService;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\order\Order;
use app\common\model\order\OrderAddress;
use app\common\model\order\OrderGood;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\Db;

class OrderController extends CheckLoginController
{

    /**
     * 列表
     */
    public function index() {
        $status = request()->get("status", "");

        $where = [];
        $where[] = ["member_id", "=", $this->member_id];
        if ($status !== "") {
            $where[] = ["status", "=", intval($status)];
        }

        $list = Order::where($where)
            ->where("member_id", $this->member_id)
            ->select();

        $order_list_map = [];
        $order_nums = [];
        foreach ($list as $v) {
            $order_num = $v->order_num;
            $order_nums[] = $order_num;
            $order_list_map[$order_num] = $v;
        }

        $order_good_list = OrderGood::where("order_num", "in", $order_nums)
            ->order("id ASC")
            ->select();

        $good_ids = [];
        $order_good_list_map = [];
        foreach ($order_good_list as $v) {
            $good_id = $v->good_id;
            $good_ids[] = $good_id;
            $order_good_list_map[$v->order_num][] = $v;
        }
        $good_ids = array_unique($good_ids);

        $good = Good::where("good_id", "in", $good_ids)
            ->select();
        $good_list_map = [];
        foreach ($good as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $good_list_map[$v->good_id] = $v;
        }

        foreach ($list as $v) {
            $temp_good_list = [];
            $temp_good_ids = $order_good_list_map[$v->order_num];
            foreach ($temp_good_ids as $v1) {
                $item = $good_list_map[$v1->good_id];
                $item["count"] = $v1["count"];
                $item["price"] = $v1["price"];
                $item["money"] = $v1["money"];
                $temp_good_list[] = $item;
            }
            $v->good_list = $temp_good_list;
        }

        return ResultVo::success($list);

    }

    /**
     * 下单
     */
    public function create() {
        $address_id = request()->post("address_id/d");
        $order_address = OrderAddress::where("id", $address_id)
            ->where("member_id", $this->member_id)
            ->find();
        if (!$order_address) {
            return ResultVo::error(ErrorCode::DATA_NOT, "改地址不存在");
        }
        $remark = request()->post("remark");
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

        $list = Good::where("good_id", "in", $good_ids)
            ->field("good_id,shop_price,store_count")
            ->select();
        if (count($list) == 0) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $sum_money = 0;
        $sum_count = 0;
        $order_good_add_data = [];
        $date_time = date("Y-m-d H:i:s");
        $expire_time = date("Y-m-d H:i:s", time() + 15 * 60 * 60);
        $order_num = OrderService::createOrderId($this->member_id);
        foreach ($list as $v) {
            $count = $good_map[$v->good_id] ?? 0;
            if ($count > $v->store_count) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "部分商品库存不足");
            }
            $price = $v->shop_price;
            $money = bcmul($price, $count, 2);
            $order_good_add_data[] = [
                "good_id" => $v->good_id,
                "order_num" => $order_num,
                "count" => $count,
                "price" => $price,
                "money" => bcmul($count, $price, 2),
                "create_time" => $date_time,
            ];
            $sum_money = bcadd($money, $sum_money, 2);
            $sum_count++;
        }
        $order_add_data = [
            "order_num" => $order_num,
            "member_id" => $this->member_id,
            "count" => $sum_count,
            "money" => $sum_money,
            "pay_money" => $sum_money,
            "name" => $order_address->name ?? "",
            "tel" => $order_address->tel ?? "",
            "address" => $order_address->address ?? "",
            "remark" => $remark,
            "status" => 0,
            "expire_time" => $expire_time,
            "create_time" => $date_time,
            "update_time" => $date_time,
        ];

        // 启动事务
        Db::startTrans();
        try {

            foreach ($good_ids as $v) {
                $count = $good_map[$v] ?? 0;
                $res = Good::where("good_id", $v)->where("store_count", ">=", $count)->setDec("store_count", $count);
                if (!$res) {
                    Db::rollback();
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "部分商品库存不足");
                }
            }

            $order = new Order();
            $order->insertGetId($order_add_data);
            $order_good = new OrderGood();
            $order_good->insertAll($order_good_add_data);

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $res_data = [];
        $res_data["order_num"] = $order_num;
        return  ResultVo::success($res_data);

    }

    /**
     * 订单详情
     */
    public function read() {

        $order_num = request()->get('order_num');
        $order = Order::where("order_num", $order_num)
            ->where("member_id", $this->member_id)
            ->find();
        if (!$order) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $order_good = OrderGood::where("order_num", $order_num)
            ->order("id ASC")
            ->select();
        $good_ids = [];
        $order_good_map = [];
        foreach ($order_good as $v) {
            $good_ids[] = $v->good_id;
            $order_good_map[$v->good_id] = $v;
        }
        $order_good_list = Good::where("good_id", "in", $good_ids)->select();
        $order_good_list_map = [];
        foreach ($order_good_list as $v) {
            $v->original_img = PublicFileUtils::createUploadUrl($v->original_img);
            $order_good_list_map[$v->good_id] = $v;
        }

        $good_list = [];
        foreach ($good_ids as $v) {
            $item = $order_good_list_map[$v];
            $oItem = $order_good_map[$v];
            $item["count"] = $oItem["count"];
            $item["price"] = $oItem["price"];
            $item["money"] = $oItem["money"];
            $good_list[] = $item;
        }

        $expire_second = strtotime($order->expire_time) - time();
        if ($expire_second <= 0 && $order->status == 0) {
            $order->status = 4;
        }
        $order->expire_second = $expire_second > 0 ? $expire_second : 0;

        $res = [];
        $res["order"] = $order;
        $res["good_list"] = $good_list;
        return ResultVo::success($res);
    }

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

        // 默认地址
        $address = OrderAddress::where("member_id", $this->member_id)
            ->where("is_default", 1)
            ->find();

        $res = [];
        $res["good_list"] = $good_list;
        $res["sum_money"] = $sum_money;
        $res["address"] = $address;
        return ResultVo::success($res);
    }

    /**
     * 取消
     */
    public function cancel() {

        $order_num = request()->post('order_num');
        $order = Order::where("order_num", $order_num)
            ->where("member_id", $this->member_id)
            ->find();
        if (!$order) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        if ($order->status != 0) {
            return ResultVo::error(ErrorCode::DATA_NOT, "该状态不能删除订单");
        }

        Order::where("order_num", $order_num)
            ->where("member_id", $this->member_id)
            ->setField("status", 4);

        return ResultVo::success();
    }

    /**
     * 确认
     */
    public function ok() {

        $order_num = request()->post('order_num');
        $order = Order::where("order_num", $order_num)
            ->where("member_id", $this->member_id)
            ->find();
        if (!$order) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        if ($order->status != 1 && $order->status != 2) {
            return ResultVo::error(ErrorCode::DATA_NOT, "该状态不能收货");
        }

        Order::where("order_num", $order_num)
            ->where("member_id", $this->member_id)
            ->setField("status", 3);

        return ResultVo::success();
    }

}