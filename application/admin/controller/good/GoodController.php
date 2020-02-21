<?php

namespace app\admin\controller\good;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\good\GoodAttrList;
use app\common\model\good\GoodCategory;
use app\common\model\good\GoodCategoryAttr;
use app\common\model\good\GoodCategoryList;
use app\common\model\good\GoodCategorySpec;
use app\common\model\good\GoodSpecList;
use app\common\utils\PublicFileUtils;
use app\common\utils\TreeUtils;
use app\common\vo\ResultVo;
use think\Db;

/**
 * 商品
 */
class GoodController extends BaseCheckUser
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
        $category_id = request()->get('category_id');
        if (is_array($category_id)){
            $category_id = end($category_id);
            $good_category_list = GoodCategoryList::where("category_id", $category_id)
                ->field("good_id")
                ->order("good_id DESC")
                ->paginate($paginate);
            $good_ids = [];
            foreach ($good_category_list as $v) {
                $good_ids[] = $v->good_id;
            }
            $good_ids = array_unique($good_ids);
            $where[] = ['good_id', 'in', $good_ids];
        }
        $lists = Good::where($where)
            ->order("good_id DESC")
            ->paginate($paginate);

        foreach ($lists as $v) {
            $v->original_img_url = PublicFileUtils::createUploadUrl($v->original_img);
        }

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);

    }

    /**
     * 详情
     */
    public function read() {
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
        // 分类
        $good_category_list = GoodCategoryList::where("good_id", $info->good_id)->field("category_id")->order("id ASC")->select();
        $category_ids = [];
        foreach ($good_category_list as $value) {
            $category_ids[] = $value->category_id;
        }
        $info->category_id = $category_ids;

        // 属性
        $good_attr_list = GoodAttrList::where("good_id", $info->good_id)->field("attr_id,value")->order("id ASC")->select();
        $attr_list = [];
        foreach ($good_attr_list as $value) {
            $attr_list[$value->attr_id] = $value->value;
        }
        $info->attr = [];
        $info->attr_list = $attr_list;

        // 规格
        $good_spec_list = GoodSpecList::where("good_id", $info->good_id)->field("spec_id,value")->order("id ASC")->select();
        $spec_list = [];
        foreach ($good_spec_list as $v) {
            $spec_value = trim($v->value) != "" ? explode(",", trim($v->value)) : [];
            $spec_list[$v->spec_id] = $spec_value;
        }
        $info->spec = [];
        $info->spec_list = $spec_list;

        $info->good_spec_list = [];
        $info->good_spec_head_list = [];


        return ResultVo::success($info);
    }

    /**
     * 分类列表
     */
    public function categoryList()
    {

        $where = [];
        $lists = GoodCategory::where($where)
            ->order("sort DESC")
            ->order("id ASC")
            ->select();

        $list = [];
        foreach ($lists as $k => $v) {
            $temp = [];
            $temp["value"] = $v["id"];
            $temp["pid"] = $v["pid"];
            $temp["label"] = $v["name"];
            $temp["level"] = $v["level"];
            $list[] = $temp;
        }

        $max_level = 2;
        $merge_list = TreeUtils::cateMerge($list,'value','pid',0, $max_level);

        $res = [];
        $res["list"] = $merge_list;
        return ResultVo::success($res);

    }

    /**
     * 属性列表
     */
    public function attrList()
    {

        $category_ids = request()->get('category_id');
        $category_id = is_array($category_ids) ? end($category_ids) : 0;
        $where = [];
        $where[] = ["category_id", "=", $category_id];
        $lists = GoodCategoryAttr::where($where)
            ->order("sort DESC")
            ->order("id ASC")
            ->select();

        foreach ($lists as $v) {
            $v->value = trim($v->value) != "" ? explode("\n", trim($v->value)) : [];
        }

        $res = [];
        $res["list"] = $lists;
        return ResultVo::success($res);

    }

    /**
     * 规格列表
     */
    public function specList()
    {

        $category_ids = request()->get('category_id');
        $category_id = is_array($category_ids) ? end($category_ids) : 0;
        $where = [];
        $where[] = ["category_id", "=", $category_id];
        $lists = GoodCategorySpec::where($where)
            ->order("id ASC")
            ->select();

        foreach ($lists as $v) {
            $v->value = trim($v->value) != "" ? explode("\n", trim($v->value)) : [];
        }

        $res = [];
        $res["list"] = $lists;
        return ResultVo::success($res);

    }

    /**
     * 添加
     */
    public function save(){
        $data = request()->post();
        if (empty($data['category_id'])
            || !is_array($data["category_id"])
            || empty($data["good_name"])
            || empty($data["shop_price"])
            || floatval($data["shop_price"]) < 0
            || empty($data["market_price"])
            || empty($data["cost_price"])
            || empty($data["original_img"])
            || empty($data["imgs"])
            || !is_array($data["imgs"])
        ){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $date_time = date("Y-m-d H:i:s");
        // return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, $data);
        $model = new Good();
        $model->good_name = $data['good_name'];
        $model->good_remark = $data['good_remark'] ?? "";
        $model->shop_price = floatval($data["shop_price"]);
        $model->market_price = floatval($data["market_price"]);
        $model->cost_price = floatval($data["cost_price"]);
        $model->weight = !empty($data["weight"]) ? floatval($data["weight"]) : 0;
        $model->volume = !empty($data["volume"]) ? floatval($data["volume"]) : 0;
        $model->virtual_sales_sum = !empty($data["virtual_sales_sum"]) ? intval($data["virtual_sales_sum"]) : 0;
        $model->original_img = $data["original_img"];
        $model->imgs = implode(",", $data["imgs"]);
        $model->details = $data["details"] ?? "";
        $model->status = !empty($data["status"]) ? 1 : 0;
        $model->create_time = $date_time;
        $model->modified_time = $date_time;
        $attr = !empty($data["attr"]) && is_array($data["attr"]) ? $data["attr"] : [];
        $attr_list = [];
        foreach ($attr as $v) {
            if (!isset($v["value"]) || $v["value"] === "") {
                continue;
            }
            if (mb_strlen($v["value"]) > 255) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "属性值不能超过255个字符");
            }
            $temp = [];
            $temp["attr_id"] = $v["id"];
            $temp["value"] = $v["value"];
            $temp["create_time"] = $date_time;
            $attr_list[] = $temp;
        }
        $spec = !empty($data["spec"]) && is_array($data["spec"]) ? $data["spec"] : [];
        $spec_list = [];
        foreach ($spec as $v) {
            if (!isset($v["value"]) || $v["value"] === "") {
                continue;
            }
            $temp_value = [];
            foreach ($v["value"] as $vv) {
                $temp_value[$vv] = $vv;
            }
            $temp = [];
            $temp["spec_id"] = $v["id"];
            $temp["value"] = $temp_value ? implode(",", array_keys($temp_value)) : "";
            $temp["create_time"] = $date_time;
            $spec_list[] = $temp;
        }

        Db::startTrans();
        try {
            $result = $model->save();

            // 分类信息
            $category_id = $data['category_id'];
            $category_list = [];
            foreach ($category_id as $k=>$v) {
                $temp = [];
                $temp["good_id"] = intval($model->good_id);
                $temp["category_id"] = $v;
                $temp["create_time"] = $date_time;
                $category_list[] = $temp;
            }
            GoodCategoryList::insertAll($category_list);

            // 属性
            foreach ($attr_list as $k=>$v) {
                $attr_list[$k]["good_id"] = intval($model->good_id);
            }
            GoodAttrList::insertAll($attr_list);

            // 规格
            foreach ($spec_list as $k=>$v) {
                $spec_list[$k]["good_id"] = intval($model->good_id);
            }
            GoodSpecList::insertAll($spec_list);
            Db::commit();
        }catch (\Exception $exception) {
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $res = [];
        $res["good_id"] = intval($model->good_id);
        return ResultVo::success($res);
    }

    /**
     * 编辑
     */
    public function edit(){
        $data = request()->post();
        // return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, $data);
        if (empty($data['good_id'])
            || empty($data["category_id"])
            || !is_array($data["category_id"])
            || empty($data["good_name"])
            || empty($data["shop_price"])
            || floatval($data["shop_price"]) < 0
            || empty($data["market_price"])
            || empty($data["cost_price"])
            || empty($data["original_img"])
            || empty($data["imgs"])
            || !is_array($data["imgs"])
        ){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $good_id = intval($data['good_id']);
        // 模型
        $model = Good::where('good_id',$good_id)
            ->field('good_id')
            ->find();
        if (!$model){
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $model->good_name = $data['good_name'];
        $model->good_remark = $data['good_remark'] ?? "";
        $model->shop_price = floatval($data["shop_price"]);
        $model->market_price = floatval($data["market_price"]);
        $model->cost_price = floatval($data["cost_price"]);
        $model->weight = !empty($data["weight"]) ? floatval($data["weight"]) : 0;
        $model->volume = !empty($data["volume"]) ? floatval($data["volume"]) : 0;
        $model->virtual_sales_sum = !empty($data["virtual_sales_sum"]) ? intval($data["virtual_sales_sum"]) : 0;
        $model->original_img = $data["original_img"];
        $model->imgs = implode(",", $data["imgs"]);
        $model->details = $data["details"] ?? "";
        $model->status = !empty($data["status"]) ? 1 : 0;
        $model->create_time = date("Y-m-d H:i:s");
        $model->modified_time = date("Y-m-d H:i:s");

        // 分类信息
        $category_id = $data['category_id'];
        $category_list = [];
        $date_time = date("Y-m-d H:i:s");
        foreach ($category_id as $k=>$v) {
            $temp = [];
            $temp["good_id"] = $good_id;
            $temp["category_id"] = $v;
            $temp["create_time"] = $date_time;
            $category_list[] = $temp;
        }
        $attr = !empty($data["attr"]) && is_array($data["attr"]) ? $data["attr"] : [];
        $attr_list = [];
        foreach ($attr as $v) {
            if (!isset($v["value"]) || $v["value"] === "") {
                continue;
            }
            if (mb_strlen($v["value"]) > 255) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "属性值不能超过255个字符");
            }
            $temp = [];
            $temp["good_id"] = $good_id;
            $temp["attr_id"] = $v["id"];
            $temp["value"] = $v["value"];
            $temp["create_time"] = $date_time;
            $attr_list[] = $temp;
        }

        $spec = !empty($data["spec"]) && is_array($data["spec"]) ? $data["spec"] : [];
        $spec_list = [];
        foreach ($spec as $v) {
            if (!isset($v["value"]) || $v["value"] === "") {
                continue;
            }
            $temp_value = [];
            foreach ($v["value"] as $vv) {
                $temp_value[$vv] = $vv;
            }
            $temp = [];
            $temp["good_id"] = $good_id;
            $temp["spec_id"] = $v["id"];
            $temp["name"] = $v["name"];
            $temp["value"] = $temp_value ? implode(",", array_keys($temp_value)) : "";
            $temp["create_time"] = $date_time;
            $spec_list[] = $temp;
        }

        // 规格
        $good_spec_list = !empty($data["good_spec_list"]) && is_array($data["good_spec_list"]) ? $data["good_spec_list"] : [];


        Db::startTrans();
        try {
            $result = $model->save();
            GoodCategoryList::where("good_id", $good_id)->delete();
            GoodCategoryList::insertAll($category_list);
            GoodAttrList::where("good_id", $good_id)->delete();
            GoodAttrList::insertAll($attr_list);
            GoodSpecList::where("good_id", $good_id)->delete();
            GoodSpecList::insertAll($spec_list);
            Db::commit();
        }catch (\Exception $exception) {
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function delete(){
        $good_id = request()->post('good_id/d');
        if (empty($good_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        Db::startTrans();
        try {
            Good::where('good_id',$good_id)->delete();
            GoodCategoryList::where("good_id", $good_id)->delete();
            GoodAttrList::where("good_id", $good_id)->delete();
            Db::commit();
        }catch (\Exception $exception) {
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

}
