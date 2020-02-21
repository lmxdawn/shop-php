<?php

namespace app\admin\controller\good;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\good\GoodCategoryAttr;
use app\common\vo\ResultVo;

/**
 * 商品类别的属性
 */
class CategoryAttrController extends BaseCheckUser
{

    /**
     * 列表
     */
    public function index()
    {

        $where = [];
        $category_id = request()->get('category_id/d', '');
        if ($category_id !== ''){
            $where[] = ['category_id','=',intval($category_id)];
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
        $lists = GoodCategoryAttr::where($where)
            ->order("id DESC")
            ->paginate($paginate);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);

    }

    /**
     * 添加
     */
    public function save(){
        $data = request()->post();
        if (empty($data['name'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $model = new GoodCategoryAttr();
        $model->name = $data['name'];
        $model->category_id = $data['category_id'] ?? 0;
        $type = !empty($data["type"]) ? 1 : 0;
        if ($type == 1 && empty($data["value"])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "请输入下拉选项的可选值");
        }
        $model->type = $type;
        $value = $type == 1 ? explode("\n", $data["value"]) : [];
        $temp_value = [];
        foreach ($value as $v) {
            if (mb_strlen($v) > 255) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "可选值不能超过255个字符");
            }
            $temp_value[$v] = $v;
        }
        $temp_value = array_keys($temp_value);
        $model->value = $value ? implode("\n", $temp_value) : "";
        $model->sort = !empty($data["sort"]) ? intval($data["sort"]) : 0;
        $model->create_time = date("Y-m-d H:i:s");
        $model->modified_time = date("Y-m-d H:i:s");
        $result = $model->save();

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $res = [];
        $res["id"] = intval($model->id);
        return ResultVo::success($res);
    }

    /**
     * 编辑
     */
    public function edit(){
        $data = request()->post();
        if (empty($data['id'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        if (empty($data['name'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = $data['id'];
        // 模型
        $model = GoodCategoryAttr::where('id',$id)
            ->field('id')
            ->find();
        if (!$model){
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $model->name = $data['name'];
        $model->category_id = $data['category_id'] ?? 0;
        $type = !empty($data["type"]) ? 1 : 0;
        if ($type == 1 && empty($data["value"])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "请输入下拉选项的可选值");
        }
        $model->type = $type;
        $value = $type == 1 ? explode("\n", $data["value"]) : [];
        $temp_value = [];
        foreach ($value as $v) {
            if (mb_strlen($v) > 255) {
                return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "选项值不能超过255个字符");
            }
            $temp_value[$v] = $v;
        }
        $temp_value = array_keys($temp_value);
        $model->value = $value ? implode("\n", $temp_value) : "";
        $model->sort = !empty($data["sort"]) ? intval($data["sort"]) : 0;
        $model->modified_time = date("Y-m-d H:i:s");
        $result = $model->save();
        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

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
        if (!GoodCategoryAttr::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

}
