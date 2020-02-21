<?php

namespace app\admin\controller\good;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\good\GoodCategorySpec;
use app\common\vo\ResultVo;

/**
 * 商品类别的规格
 */
class CategorySpecController extends BaseCheckUser
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
        $lists = GoodCategorySpec::where($where)
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
        $model = new GoodCategorySpec();
        $model->category_id = $data['category_id'] ?? 0;
        $model->name = $data['name'];
        $is_add = !empty($data["is_add"]) ? 1 : 0;
        $model->is_add = $is_add;
        $value = !empty($data["value"]) ? explode("\n", $data["value"]) : [];
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
        $model = GoodCategorySpec::where('id',$id)
            ->field('id')
            ->find();
        if (!$model){
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $model->category_id = $data['category_id'] ?? 0;
        $model->name = $data['name'];
        $is_add = !empty($data["is_add"]) ? 1 : 0;
        $model->is_add = $is_add;
        $value = !empty($data["value"]) ? explode("\n", $data["value"]) : [];
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
        if (!GoodCategorySpec::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

}
