<?php

namespace app\admin\controller\good;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\good\GoodCategory;
use app\common\utils\PublicFileUtils;
use app\common\utils\TreeUtils;
use app\common\vo\ResultVo;
use think\Db;

/**
 * 商品分类
 */
class CategoryController extends BaseCheckUser
{

    /**
     * 列表
     */
    public function index()
    {

        $where = [];
        $lists = GoodCategory::where($where)
            ->order("sort DESC")
            ->order("id ASC")
            ->select();

        foreach ($lists as $k => $v) {
            $v['pic_url'] = PublicFileUtils::createUploadUrl($v['pic']);
        }

        $res = [];
        $merge_list = TreeUtils::cateMerge($lists,'id','pid',0);
        $tree_list = TreeUtils::cateTree($lists,'id','pid',0);
        $res['list'] = $merge_list;
        $res['tree_list'] = $tree_list;
        return ResultVo::success($res);

    }

    /**
     * 添加
     */
    public function save(){
        $data = request()->post();
        if (empty($data["name"])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $pid = !empty($data['pid']) ? intval($data['pid']) : 0;
        $pic = !empty($data['pic']) ? $data['pic'] : "";
        $sort = !empty($data['sort']) ? intval($data['sort']) : 0;
        $is_show = !empty($data['is_show']) ? 1 : 0;
        $is_recommend = !empty($data['is_recommend']) ? 1 : 0;
        $level = 1;
        if ($pid > 0) {
            $pInfo = GoodCategory::where("Id", $pid)->find();
            if (!$pInfo) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
            }
            if ($pInfo->level >= 3) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
            }
            $level = $pInfo->level == 1 ? 2 : 3;
        }


        $save_data = [
            "pid" => $pid,
            "level" => $level,
            "name" => $data["name"],
            "pic" => $pic,
            "sort" => $sort,
            "is_show" => $is_show,
            "is_recommend" => $is_recommend,
            "create_time" => date("Y-m-d H:i:s"),
            "update_time" => date("Y-m-d H:i:s"),
        ];
        $good_category = new GoodCategory();
        $id = $good_category->insertGetId($save_data);

        $res = [];
        $res["id"] = $id;
        $res["level"] = $level;
        return ResultVo::success($res);
    }

    /**
     * 编辑
     * @throws
     */
    public function edit(){
        $data = request()->post();

        if (empty($data["id"])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = intval($data["id"]);
        if (empty($data["name"])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $pic = !empty($data['pic']) ? $data['pic'] : "";
        $sort = !empty($data['sort']) ? intval($data['sort']) : 0;
        $is_show = !empty($data['is_show']) ? 1 : 0;
        $is_recommend = !empty($data['is_recommend']) ? 1 : 0;
        $save_data = [
            "name" => $data["name"],
            "pic" => $pic,
            "sort" => $sort,
            "is_show" => $is_show,
            "is_recommend" => $is_recommend,
            "update_time" => date("Y-m-d H:i:s"),
        ];
        GoodCategory::where("id", $id)->update($save_data);

        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function delete(){
        $id = request()->post('id/d');
        if (empty($id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 下面有子节点，不能删除
        $sub = GoodCategory::where('pid',$id)->field('id')->find();
        if ($sub){
            return ResultVo::error(ErrorCode::NOT_NETWORK, "该分类下面有子节点");
        }

        if (!GoodCategory::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

}
