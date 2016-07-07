<?php
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model
{
    /**获取角色数据
     * @return mixed
     */
    public function getList(){
        return $this->where(['status'=>1])->select();
    }

    public function addRole(){
        unset($this->data[$this->getPk()]);  //排空
        //开启事务
        $this->startTrans();
        //保存信息
        if(($role_id=$this->add())===false){   //保存失败
            $this->rollback();   //事物回滚
            return false;       //返回失败数据结果
        }

        //角色与权限相关，添加后需要将角色与权限进行关联
        if($this->save_permission($role_id)===false){
            $this->rollback();
            return false;
        }

        //执行
        $this->commit();
        return true;
    }

    /**
     * 修改角色信息
     * @return bool
     */
    public function updateRole(){
        unset($this->data[$this->getPk()]);  //排空
        //开启事务
        $this->startTrans();
        $request_data = $this->data;   //这里用$request_data功能和data一样  避免与data冲突
        //保存信息
        if($this->save()===false){   //保存失败
            $this->rollback();   //事物回滚
            return false;       //返回失败数据结果
        }

        //角色与权限相关，修改后需要将角色与权限进行关联
        if($this->save_permission($request_data['id'],false)===false){
            $this->rollback();
            return false;
        }

        //执行
        $this->commit();
        return true;
    }

    /** 删除角色  和相关权限
     * @param $id
     * @return bool
     */
    public function deleteRole($id){
//        unset($this->data[$this->getPk()]);
        //开启事务
        $this->startTrans();
        //删除角色
        if($this->delete($id)===false){   //保存失败
            $this->rollback();   //事物回滚
            return false;       //返回失败数据结果
        }
        //删除角色后，还有中间记录表
            //实例化中间记录表
        $role_permission_model = M('RolePermission');
            //根据条件删除  role_id
        if($role_permission_model->where(['role_id'=>$id])->delete() === false){
            $this->error = '删除权限失败';
            $this->rollback();
            return false;
        }
        //执行
        $this->commit();
        return true;
    }



    //建立save_permission方法   保存角色和权限的关系
    /**
     * @param $role_id
     * @param bool|true $is_new
     * @return bool
     */
    private function save_permission($role_id,$is_new=true){
        //实例化角色和权限的中间标
        $role_permission_model = M('RolePermission');
        if(!$is_new){       //如果是修改,就先删除原来的
            if($role_permission_model->where(['role_id'=>$role_id])->delete()===false){  //没有取到值
                $this->error = '删除原有权限失败！';
                return false;
            }
        }
        //收集用户提交的权限列表
        $permission_ids = I('post.permission_id');   //将用户的id值I方法收集
        if(empty($permission_ids)){   //如果是空操作
            return true;
        }
        $data = [];
        //将数据保存后 将要插入的数据的字段准备好 遍历数组
        foreach($permission_ids as $permission_id){     //Model中一样用for foreach 遍历数组
            $data[] = [
                'role_id'=>$role_id,
                'permission_id'=>$permission_id,
            ];
        }
        if($role_permission_model->addAll($data)===false){    //添加失败
            $this->error = '保存权限失败';
            return false;
        }
        return true;
    }

    /**
     * 获取角色和权限
     * @param $id
     * @return type
     */
    public function getRoleInfo($id){
        //根据条件查找数据
        $row = $this->find($id);
        //根据中间标获取对应的值   多对多
        $row['permission_ids'] = json_encode(M('RolePermission')->where(['role_id'=>$id])->getField('permission_id',true));
        return $row;
    }

}