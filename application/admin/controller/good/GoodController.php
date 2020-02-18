<?php

namespace app\admin\controller\good;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\good\Good;
use app\common\model\good\GoodCategory;
use app\common\model\good\GoodCategoryList;
use app\common\utils\PublicFileUtils;
use app\common\utils\TreeUtils;
use app\common\vo\ResultVo;

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
        $category_id = request()->get('category_id/a');
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
            $imgs = explode(",", $v->imgs);
            $temp = [];
            foreach ($imgs as $pic_key => $pic) {
                $temp[] = PublicFileUtils::createUploadUrl($pic);
            }
            $v->imgs = $imgs;
            $v->imgs_url = $temp;
            $v->original_img_url = PublicFileUtils::createUploadUrl($v->original_img);
            $good_category_list = GoodCategoryList::where("good_id", $v->good_id)->field("category_id")->order("good_id DESC")->select();
            $category_ids = [];
            foreach ($good_category_list as $value) {
                $category_ids[] = $value->category_id;
            }
            $v->category_id = $category_ids;
        }

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);

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
            $temp["leaf"] = $v["level"] >= 2;
            $list[] = $temp;
        }

        $merge_list = TreeUtils::cateMerge($list,'value','pid',0, true);

        $res = [];
        $res["list"] = $merge_list;
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
        $model->create_time = date("Y-m-d H:i:s");
        $model->modified_time = date("Y-m-d H:i:s");
        $result = $model->save();

        // 分类信息
        $category_id = $data['category_id'];
        $category_list = [];
        $date_time = date("Y-m-d H:i:s");
        foreach ($category_id as $k=>$v) {
            $temp = [];
            $temp["good_id"] = intval($model->good_id);
            $temp["category_id"] = $v;
            $temp["sort"] = $k;
            $temp["create_time"] = $date_time;
            $category_list[] = $temp;
        }
        GoodCategoryList::insertAll($category_list);

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
        $result = $model->save();

        // 分类信息
        $category_id = $data['category_id'];
        $category_list = [];
        $date_time = date("Y-m-d H:i:s");
        foreach ($category_id as $k=>$v) {
            $temp = [];
            $temp["good_id"] = $good_id;
            $temp["category_id"] = $v;
            $temp["sort"] = $k;
            $temp["create_time"] = $date_time;
            $category_list[] = $temp;
        }
        GoodCategoryList::where("good_id", $good_id)->delete();
        GoodCategoryList::insertAll($category_list);

        if (!$result){
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
        if (!Good::where('good_id',$good_id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        GoodCategoryList::where("good_id", $good_id)->delete();

        return ResultVo::success();

    }

}
