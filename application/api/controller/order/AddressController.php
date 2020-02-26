<?php


namespace app\api\controller\order;


use app\api\controller\CheckLoginController;
use app\common\enums\ErrorCode;
use app\common\model\order\OrderAddress;
use app\common\vo\ResultVo;

class AddressController extends CheckLoginController
{

    public function index() {

        $page = request()->get('page/d');
        $count = request()->get('count/d');
        $offset = $page <= 0 ? 1 : $page;
        $limit = $count > 50 || $count <= 0 ? 50 : $count;
        $offset = ($offset - 1) * $limit;
        $list = OrderAddress::where("member_id", $this->member_id)
            ->order("create_time DESC")
            ->limit($offset, $limit)
            ->select();

        return ResultVo::success($list);
    }

    public function read() {

        $id = request()->get('id/d');
        $info = OrderAddress::where("id", $id)
            ->where("member_id", $this->member_id)
            ->find();

        if (!$info) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }

        return ResultVo::success($info);
    }


    public function save() {
        $data = request()->post();
        $id = !empty($data["id"]) ? intval($data["id"]) : 0;

        if (empty($data["name"])
            || empty($data["tel"])
            || empty($data["province"])
            || empty($data["city"])
            || empty($data["area"])
            || empty($data["address"])
        ) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $save = [];
        $save["name"] = $data["name"];
        $save["tel"] = $data["tel"];
        $save["province"] = $data["province"];
        $save["city"] = $data["city"];
        $save["area"] = $data["area"];
        $save["address"] = $data["address"];
        $save["is_default"] = !empty($data["is_default"]) ? 1 : 0;
        $save["update_time"] = date("Y-m-d H:i:s");
        if ($id) {
            $info = OrderAddress::where("id", $id)
                ->where("member_id", $this->member_id)
                ->find();

            if (!$info) {
                return ResultVo::error(ErrorCode::DATA_NOT);
            }
            OrderAddress::where("id", $id)->update($save);
        } else {
            $info = new OrderAddress();
            $save["member_id"] = $this->member_id;
            $save["create_time"] = date("Y-m-d H:i:s");
            $info->insert($save);
        }


        return ResultVo::success();
    }

    public function delete() {
        $data = request()->post();
        $id = !empty($data["id"]) ? intval($data["id"]) : 0;

        if (empty($id)){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        OrderAddress::where("id", $id)->where("member_id", $this->member_id)->delete();


        return ResultVo::success();
    }

}