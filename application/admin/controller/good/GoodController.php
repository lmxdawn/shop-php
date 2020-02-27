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
use app\common\model\good\GoodSku;
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
            ->order("sort DESC")
            ->order("good_id DESC")
            ->paginate($paginate);

        foreach ($lists as $v) {
            $imgs = explode(",", $v->imgs);
            $temp = [];
            foreach ($imgs as $pic_key => $pic) {
                $temp[] = PublicFileUtils::createUploadUrl($pic);
            }
            $v->imgs = $imgs;
            $v->imgs_url = $temp;
            $v->original_img_url = PublicFileUtils::createUploadUrl($v->original_img);
            $v->is_new = $v->is_new == 1 ? true : false;
            $v->is_recommend = $v->is_recommend == 1 ? true : false;
            $v->is_hot = $v->is_hot == 1 ? true : false;
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

        // 商品推荐
        $info->is_new = $info->is_new == 1 ? true : false;
        $info->is_recommend = $info->is_recommend == 1 ? true : false;
        $info->is_hot = $info->is_hot == 1 ? true : false;

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
        $good_spec_head_list = [];
        $good_spec_list = GoodSpecList::where("good_id", $info->good_id)->field("spec_id,name,value")->order("id ASC")->select();
        $spec_list = [];
        foreach ($good_spec_list as $v) {
            $spec_value = trim($v->value) != "" ? explode(",", trim($v->value)) : [];
            $spec_list[$v->spec_id] = $spec_value;
            $good_spec_head_list[] = [
                "id" => $v["spec_id"],
                "name" => $v["name"],
            ];
        }
        $info->spec = [];
        $info->spec_list = $spec_list;

        $good_sku = GoodSku::where("good_id", $info->good_id)->order("id ASC")->select();
        $good_sku_list = [];
        foreach ($good_sku as $v) {
            $spec_value_list = trim($v->spec_value_list) != "" ? explode(",", trim($v->spec_value_list)) : [];
            unset($v->spec_value_list);
            $v["spec_list"] = $spec_value_list;
            $good_sku_list[] = $v;
        }
        $info->good_spec_list = $good_sku_list;
        $info->good_spec_head_list = $good_spec_head_list;


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
        $sku_refresh = $data["sku_refresh"] ?? false;
        $store_count = $data["store_count"] ?? 0;
        $is_new = !empty($data["is_new"]) ? 1 : 0;
        $new_sort = !empty($data["new_sort"]) ? intval($data["new_sort"]) : 0;
        $is_recommend = !empty($data["is_recommend"]) ? 1 : 0;
        $recommend_sort = !empty($data["recommend_sort"]) ? intval($data["recommend_sort"]) : 0;
        $is_hot = !empty($data["is_hot"]) ? 1 : 0;
        $hot_sort = !empty($data["hot_sort"]) ? intval($data["hot_sort"]) : 0;
        $model = new Good();
        $model->good_name = $data['good_name'];
        $model->good_remark = $data['good_remark'] ?? "";
        $model->shop_price = floatval($data["shop_price"]);
        $model->market_price = floatval($data["market_price"]);
        $model->cost_price = floatval($data["cost_price"]);
        $model->unit = $data["unit"] ?? "";
        $model->weight = !empty($data["weight"]) ? floatval($data["weight"]) : 0;
        $model->volume = !empty($data["volume"]) ? floatval($data["volume"]) : 0;
        $model->store_count = intval($store_count);
        $model->virtual_sales_sum = !empty($data["virtual_sales_sum"]) ? intval($data["virtual_sales_sum"]) : 0;
        $model->original_img = $data["original_img"];
        $model->imgs = implode(",", $data["imgs"]);
        $model->details = $data["details"] ?? "";
        $model->status = !empty($data["status"]) ? 1 : 0;
        $model->is_new = $is_new;
        $model->new_sort = $new_sort;
        $model->is_recommend = $is_recommend;
        $model->recommend_sort = $recommend_sort;
        $model->is_hot = $is_hot;
        $model->hot_sort = $hot_sort;
        $model->sort = $data["sort"] ?? 0;
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

        $spec_list = [];
        $sku_list = [];
        if ($sku_refresh) {
            $spec = !empty($data["spec"]) && is_array($data["spec"]) ? $data["spec"] : [];
            $spec_list = [];
            foreach ($spec as $v) {
                if (empty($v["value"])) {
                    continue;
                }
                $temp_value = [];
                foreach ($v["value"] as $vv) {
                    if (stripos($vv, ",") !== false) {
                        return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "规格名称不能包含英文逗号，请换成中文逗号");
                    }
                    $temp_value[$vv] = $vv;
                }
                $temp = [];
                $temp["spec_id"] = $v["id"];
                $temp["value"] = $temp_value ? implode(",", array_keys($temp_value)) : "";
                $temp["create_time"] = $date_time;
                $spec_list[] = $temp;
            }

            // 规格
            $good_spec_list = !empty($data["good_spec_list"]) && is_array($data["good_spec_list"]) ? $data["good_spec_list"] : [];
            $sku_list = [];
            foreach ($good_spec_list as $v) {
                $price = floatval($v["price"]);
                $cost_price = floatval($v["cost_price"]);
                $stock = intval($v["stock"]);
                $spec_list = $v["spec_list"];
                if ($price < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU价格不能小于0");
                }
                if ($cost_price < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU成本价格不能小于0");
                }
                if ($stock < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU库存不能小于0");
                }
                if (!is_array($spec_list) || empty($spec_list)) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "请至少选一个规格");
                }
                $temp = [];
                $temp["price"] = $price;
                $temp["cost_price"] = $cost_price;
                $temp["stock"] = $stock;
                $temp["spec_value_list"] = implode(",", $spec_list);
                $temp["create_time"] = $date_time;
                $sku_list[] = $temp;
            }
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
            if ($category_list) {
                GoodCategoryList::insertAll($category_list);
            }

            // 属性
            foreach ($attr_list as $k=>$v) {
                $attr_list[$k]["good_id"] = intval($model->good_id);
            }
            if ($attr_list) {
                GoodAttrList::insertAll($attr_list);
            }

            // 规格
            foreach ($spec_list as $k=>$v) {
                $spec_list[$k]["good_id"] = intval($model->good_id);
            }
            if ($spec_list) {
                GoodSpecList::insertAll($spec_list);
            }

            // 规格
            foreach ($sku_list as $k=>$v) {
                $sku_list[$k]["good_id"] = intval($model->good_id);
            }
            if ($sku_list) {
                GoodSku::insertAll($sku_list);
            }
            Db::commit();
        }catch (\Exception $exception) {
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK, $exception->getLine());
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
        $sku_refresh = $data["sku_refresh"] ?? false;
        $store_count = $data["store_count"] ?? 0;
        $is_new = !empty($data["is_new"]) ? 1 : 0;
        $new_sort = !empty($data["new_sort"]) ? intval($data["new_sort"]) : 0;
        $is_recommend = !empty($data["is_recommend"]) ? 1 : 0;
        $recommend_sort = !empty($data["recommend_sort"]) ? intval($data["recommend_sort"]) : 0;
        $is_hot = !empty($data["is_hot"]) ? 1 : 0;
        $hot_sort = !empty($data["hot_sort"]) ? intval($data["hot_sort"]) : 0;
        $model->good_name = $data['good_name'];
        $model->good_remark = $data['good_remark'] ?? "";
        $model->shop_price = floatval($data["shop_price"]);
        $model->market_price = floatval($data["market_price"]);
        $model->cost_price = floatval($data["cost_price"]);
        $model->unit = $data["unit"] ?? "";
        $model->weight = !empty($data["weight"]) ? floatval($data["weight"]) : 0;
        $model->volume = !empty($data["volume"]) ? floatval($data["volume"]) : 0;
        $model->store_count = intval($store_count);
        $model->virtual_sales_sum = !empty($data["virtual_sales_sum"]) ? intval($data["virtual_sales_sum"]) : 0;
        $model->original_img = $data["original_img"];
        $model->imgs = implode(",", $data["imgs"]);
        $model->details = $data["details"] ?? "";
        $model->status = !empty($data["status"]) ? 1 : 0;
        $model->is_new = $is_new;
        $model->new_sort = $new_sort;
        $model->is_recommend = $is_recommend;
        $model->recommend_sort = $recommend_sort;
        $model->is_hot = $is_hot;
        $model->hot_sort = $hot_sort;
        $model->sort = $data["sort"] ?? 0;
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

        $spec_list = [];
        $sku_list = [];
        if ($sku_refresh) {
            $spec = !empty($data["spec"]) && is_array($data["spec"]) ? $data["spec"] : [];
            $spec_list = [];
            foreach ($spec as $v) {
                if (empty($v["value"])) {
                    continue;
                }
                $temp_value = [];
                foreach ($v["value"] as $vv) {
                    if (stripos($vv, ",") !== false) {
                        return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "规格名称不能包含英文逗号，请换成中文逗号");
                    }
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
            $sku_list = [];
            foreach ($good_spec_list as $v) {
                $price = floatval($v["price"]);
                $cost_price = floatval($v["cost_price"]);
                $stock = intval($v["stock"]);
                $temp_spec_list = $v["spec_list"];
                if ($price < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU价格不能小于0");
                }
                if ($cost_price < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU成本价格不能小于0");
                }
                if ($stock < 0) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "SKU库存不能小于0");
                }
                if (!is_array($temp_spec_list) || empty($temp_spec_list)) {
                    return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "请至少选一个规格");
                }
                $temp = [];
                $temp["good_id"] = $good_id;
                $temp["price"] = $price;
                $temp["cost_price"] = $cost_price;
                $temp["stock"] = $stock;
                $temp["spec_value_list"] = implode(",", $temp_spec_list);
                $temp["create_time"] = $date_time;
                $sku_list[] = $temp;
            }
        }


        Db::startTrans();
        try {
            $result = $model->save();
            GoodCategoryList::where("good_id", $good_id)->delete();
            if ($category_list) {
                GoodCategoryList::insertAll($category_list);
            }
            GoodAttrList::where("good_id", $good_id)->delete();
            if ($attr_list) {
                GoodAttrList::insertAll($attr_list);
            }
            if ($sku_refresh) {
                GoodSpecList::where("good_id", $good_id)->delete();
                if ($spec_list) {
                    GoodSpecList::insertAll($spec_list);
                }
                GoodSku::where("good_id", $good_id)->delete();
                if ($sku_list) {
                    GoodSku::insertAll($sku_list);
                }
            }
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
            GoodSpecList::where("good_id", $good_id)->delete();
            GoodSku::where("good_id", $good_id)->delete();
            Db::commit();
        }catch (\Exception $exception) {
            Db::rollback();
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

    /**
     * 修改状态
     */
    public function status(){
        $good_id = request()->post('good_id/d');
        $status = request()->post('status/d');
        $status = $status ? 1 : 0;
        if (empty($good_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        Good::where('good_id',$good_id)->setField("status", $status);

        return ResultVo::success();

    }

    /**
     * 修改新品
     */
    public function is_new(){
        $good_id = request()->post('good_id/d');
        $is_new = request()->post('is_new/d');
        $is_new = $is_new ? 1 : 0;
        if (empty($good_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        Good::where('good_id',$good_id)->setField("is_new", $is_new);

        return ResultVo::success();

    }

    /**
     * 修改推荐
     */
    public function is_recommend(){
        $good_id = request()->post('good_id/d');
        $is_recommend = request()->post('is_recommend/d');
        $is_recommend = $is_recommend ? 1 : 0;
        if (empty($good_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        Good::where('good_id',$good_id)->setField("is_recommend", $is_recommend);

        return ResultVo::success();

    }

    /**
     * 修改热卖
     */
    public function is_hot(){
        $good_id = request()->post('good_id/d');
        $is_hot = request()->post('is_hot/d');
        $is_hot = $is_hot ? 1 : 0;
        if (empty($good_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        Good::where('good_id',$good_id)->setField("is_hot", $is_hot);

        return ResultVo::success();

    }

}
