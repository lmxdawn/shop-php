<?php


namespace app\admin\controller\order;


use app\admin\controller\BaseCheckUser;
use app\admin\service\MemberService;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\good\GoodCategoryList;
use app\common\model\order\Order;
use app\common\model\order\OrderGood;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

class OrderController extends BaseCheckUser
{


    /**
     * 列表
     */
    public function index()
    {
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];

        $where = [];
        $status = request()->get("status", "");
        if ($status !== "") {
            $where[] = ["status", "=", intval($status)];
        }

        $member_id = request()->get("member_id", "");
        if ($member_id !== "") {
            $where[] = ["member_id", "=", intval($member_id)];
        }

        $order_num = request()->get("order_num", "");
        if ($order_num !== "") {
            $where[] = ["order_num", "=", $order_num];
        }

        $lists = Order::where($where)
            ->order("id DESC")
            ->paginate($paginate);

        $member_ids = [];
        foreach ($lists as $v) {
            $member_ids[] = $v->member_id;
        }
        $member_list_map = MemberService::listMemberInfoByMemberIdIn($member_ids);
        foreach ($lists as $v) {
            $member = $member_list_map[$v->member_id] ?? [];
            $v->name = $member->name ?? "";
            $v->avatar = $member->avatar ?? "";
        }

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);

    }

    /**
     * 订单详情
     */
    public function read() {

        $order_num = request()->get('order_num');
        $order = Order::where("order_num", $order_num)->find();
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
            $imgs = explode(",", $v->imgs);
            $temp = [];
            foreach ($imgs as $pic_key => $pic) {
                $temp[] = PublicFileUtils::createUploadUrl($pic);
            }
            $v->imgs = $imgs;
            $v->imgs_url = $temp;
            $v->original_img_url = PublicFileUtils::createUploadUrl($v->original_img);
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
     * 发货
     */


    /**
     * 确认
     */
    public function push() {

        $order_num = request()->post('order_num');
        $order = Order::where("order_num", $order_num)
            ->find();
        if (!$order) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        if ($order->status != 1) {
            return ResultVo::error(ErrorCode::DATA_NOT, "该状态不能发货");
        }

        Order::where("order_num", $order_num)
            ->where("status", 1)
            ->setField("status", 2);

        return ResultVo::success();
    }

}