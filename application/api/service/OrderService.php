<?php


namespace app\api\service;


class OrderService
{

    /**
     * 创建订单号
     * @param $id
     * @return string
     */
    public static function createOrderId($id)
    {
        // 第三种
        $id = str_pad($id, 3, '0', STR_PAD_LEFT);
        if (strlen($id) > 3) {
            $id = substr($id, 0, 8);
        }
        $type = 1;
        $date = date("His");
        $id = $type . $date . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT) . $id;
        return $id;
    }
}