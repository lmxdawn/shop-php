<?php


namespace app\api\controller\order;


use app\api\controller\CheckLoginController;
use app\common\enums\ErrorCode;
use app\common\model\order\Order;
use app\common\model\order\OrderAddress;
use app\common\model\wx\WeChatWap;
use app\common\utils\PayConfigUtils;
use app\common\vo\ResultVo;
use think\Db;
use think\facade\Hook;

class PayController
{

    /**
     * 微信小程序支付
     */
    public function weChatMiniApp() {
        $login_info = Hook::exec('app\\common\\behavior\\CheckUserLoginBehavior', []);
        $member_id = intval($login_info["member_id"]);
        $pay_type = WeChatWap::CONF_NAME_MINIAPP; // 小程序
        $order_num = request()->post('order_num');
        $order = Order::where("order_num", $order_num)
            ->where("member_id", $member_id)
            ->find();
        if (!$order) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        if ($order->status != 1) {
            return ResultVo::error(ErrorCode::DATA_NOT, "只有待支付的订单才能发起支付");
        }
        $money = $order->pay_money;
        // 微信支付
        $order_biz = [
            'out_trade_no' => $order_num,
            'total_fee' => bcmul($money, 100),
            'body' => "购买商品",
            'spbill_create_ip' => request()->ip(0, true),
        ];
        $conf = [];
        $conf["notify_url"] = url("api/order/pay/weChatMiniAppNotify", '', false, true);
        $gateway = PayConfigUtils::weChatPay($pay_type, $conf);
        $pay = $gateway->miniapp($order_biz);
        $pay->add("create_time", time());
        return ResultVo::success($pay);

    }

    /**
     * 微信小程序回调
     */
    public function weChatMiniAppNotify() {

        $pay_type = WeChatWap::CONF_NAME_MINIAPP; // 小程序
        $gateway = PayConfigUtils::weChatPay($pay_type);

        try{
            $data = $gateway->verify();
            if (empty($data["openid"])) {
                return "fail";
            }
            $order_num = intval($data['out_trade_no']);
            $total_fee = $data['total_fee'];

            $order = Order::where("order_num", $order_num)
                ->find();

            if (!$order || $order->status != 0) {
                return "success";
            }
            $money = bcdiv($total_fee, 100, 2);
            // 金额不相等
            if ($money != $order->pay_money) {
                return "fail";
            }

            // 启动事务
            Db::startTrans();
            try {

                $member_id = $order->member_id;
                // 修改订单号
                $up_order = [];
                $up_order["status"] = 1;
                $order_res = Order::where("order_num", $order_num)->where("status", 0)->update($up_order);
                if (!$order_res) {
                    Db::rollback();
                    return "fail";
                }

                // 提交事务
                Db::commit();

            }catch (\Exception $exception) {
                // 回滚事务
                Db::rollback();
                return "fail";
            }

        } catch (\Exception $e) {
            return "fail";
        }

        return "success";


    }


}