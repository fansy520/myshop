<?php
namespace Admin\Model;
use Admin\Service\NestedSets;
use Think\Model;

/**
 * Class PermissionModel
 * @package Admin\Model
 */
class PermissionModel extends Model
{
    //自动验证权限
    protected $_validata = [
        ['name', 'require', '权限名称不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH],
        ['parent_id', 'require', '父级权限不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
    ];
    /**
     * 获取分类列表
     */
    public function getList() {
        return $this->where(['status'=>1])->order('lft')->select();
    }

    /**
     * @return bool
     * 采用 nestedsets 插件添加权限
     */
    public function addPermission(){
        unset($this->data[$this->getPk()]);   //排除空值
        $orm = D('NestedSetsMysql', 'Logic'); //实例化层级关系的模型 采用nestedsets插件
        $nestedsets=new NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->insert($this->data['parent_id'], $this->data, 'bottom') === false) {
            $this->error = '添加失败';
            return false;
        }
        return true;

    }

    /**
     * @return bool
     * 保存权限  注意父级权限
     */
    public function savePermission(){
        $request_data = $this->data;   //保存修改前的数据
        //判断是否修改了父级权限
        $parent_id  = $this->getFieldById($request_data['id'], 'parent_id');
        if($parent_id != $request_data['parent_id']){  //如果修改父级的权限  计算新的节点
            $orm = D('NestedSetsMysql','Logic'); //实例化层级关系的模型 采用nestedsets插件
            $nestedSets=new NestedSets($orm,$this->trueTableName,'lft', 'rght','parent_id','id','level');

            if($nestedSets->moveUnder($request_data['id'], $request_data['parent_id'],'bottom') === false){
                $this->error = '父级分类不合法';
                return false;
            }
        }
        //如果没有修改,直接保存
        return $this->save();

    }

    public function deletePermission($id){
        $orm = D('NestedSetsMysql', 'Logic'); //实例化层级关系的模型 采用nestedsets插件
        $nestedSets=new NestedSets($orm,$this->trueTableName,'lft','rght','parent_id','id','level');
        if ($nestedSets->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        }else{
            return true;
        }
    }



}