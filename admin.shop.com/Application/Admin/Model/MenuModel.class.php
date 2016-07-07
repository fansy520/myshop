<?php
namespace Admin\Model;

use Admin\Service\NestedSets;
use Think\Model;

/**
 * Class MenuModel
 * @package Admin\Model
 */
class MenuModel extends Model
{
    /**
     * 获取数据
     * @return mixed
     */
    public function getList(){
        return $this->where(['status'=>1])->select();
    }


    public function getMenus() {
        //获取当前用户的权限列表
        $pids = permission_ids();
        //如果没有权限列表，范湖一个空数组
        if(!$pids){
            return array();
        }
        //获取所有可访问有权限的菜单
        return $this->field('id,name,path,parent_id')->distinct(true)->alias('m')->join('left join __MENU_PERMISSION__ as mp ON m.`id`=mp.`menu_id`')->where(['permission_id' => ['in', $pids]])->select();
    }


    /**
     * 获取权限的关联信息   基本信息
     * @param $id
     * @return mixed
     */
    public function getMenuInfo($id) {
        $row = $this->find($id);  //获取一条数据
        //下旬出关联字段的信息  中间表MenuPermission
        $row['permission_ids'] = json_encode(M('MenuPermission')->where(['menu_id'=>$id])->getField('permission_id',true));
        return $row;
    }

    /**
     * 菜单的添加
     * @return bool
     */
    public function addMenu(){
        unset($this->data[$this->getPk()]);
        $this->startTrans();
        //保存菜单信息    使用nestedsets添加菜单   （树形结构）
        $orm = D('NestedSetsMysql','Logic');
        //创建一个nestedsets对象
        $nestedSets = new NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        if(($menu_id = $nestedSets->insert($this->data['parent_id'], $this->data, 'bottom'))===false){
            $this->error = '添加菜单失败';
            $this->rollback();
            return false;
        }

        //添加菜单时，权限限制  中间表 MenuPermission  id为媒介
        //保存关联的权限
        $permission_ids = I('post.permission_id');   //获取权限列表
        if($permission_ids){
            $data = [];
            foreach($permission_ids as $permission_id){   //将权限列表遍历出来
                $data[] = [                               //保存关联的字段，主键
                    'menu_id'=>$menu_id,
                    'permission_id'=>$permission_id,
                ];
            }
            //如果关联成功，则执行添加
            if(M('MenuPermission')->addAll($data) === false){
                $this->error = '保存权限失败';
                $this->rollback();
                return false;
            }
        }

        $this->commit();
        return true;
    }


    /**
     * 修改菜单
     * @return bool
     */
    public function saveMenu(){
        $request_data=$this->data;
//        dump($request_data);exit;
        $this->startTrans();         //开启事务
        //保存菜单时 需要判断是否修改父级权限
           //取得父级菜案id
           $parent_id=$this->getFieldById($request_data['id'],'parent_id');
//        echo $this->getLastSql();exit;
//        dump($parent_id);exit;
        if($parent_id!=$request_data['parent_id']){   //判断如果修改了父级，则重新计算左右节点
            //实例化对象
            $orm=D('NestedSetsMysql','Logic');
            //利用 nestedsets  方法查找计算数据
            $nestedsets=new NestedSets($orm,$this->trueTableName,'lft','rght','parent_id', 'id', 'level');

            //判断添加的id和父级id是否合法
            if($nestedsets->moveUnder($request_data['id'], $request_data['parent_id'], 'bottom') === false){
                $this->error='父级分类不合法';
//                $this->rollback();
                return false;
            }
        }

        //保存菜单时，同时他的权限也会跟着改变
        $permission_ids=I('post.permission_id');
//        dump($permission_ids);exit;
        //  找到中间表
        $menu_permission_model=M('MenuPermission');
//            dump($menu_permission_model);exit;
        //修改保存后  先删除原有的权限
        $menu_permission_model->where(['menu_id'=>$request_data['id']])->delete();
//        var_dump($rows);exit;
        if($permission_ids){    //获取到权限值
//            var_dump($permission_ids);exit;
            $data=[];           //准备数组 装
            foreach($permission_ids as $permission_id){
                $data [] =  [
                    'menu_id'=>$request_data['id'],    //准备修改相关联的字段
                    'permission_id'=>$permission_id,
                ];

            }

//            var_dump($data);exit;
//            $rows=$menu_permission_model->addAll($data);
//            var_dump($rows);exit;
            if($menu_permission_model->addAll($data)===false){
//                echo $menu_permission_model->getLastSql();exit;
//                var_dump($menu_permission_model->addAll($data));exit;
                $this->error = '保存权限失败';
                $this->rollback();
                return false;
            }

        }
        if($this->save()===false){
            $this->rollback();
            return false;
        }

        $this->commit();
        return true;

    }

    public function deleteMenu($id){
        $this->startTrans();

        //获取当前菜单的信息  同时需要获取所有的后代菜单
        $info = $this->field('lft,rght')->find($id);
        $cond  = [
            'lft'  => ['egt', $info['lft']],
            'rght' => ['elt', $info['rght']],
        ];
        $menu_ids = $this->where($cond)->getField('id', true);

        //采用$nesetedsets计算
        $orm        = D('NestedSetsMysql', 'Logic');
        $nesetedsets = new NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nesetedsets->delete($id) === false) {
            $this->error = '删除菜单失败';
            $this->rollback();
            return false;
        }

        //删除关联的权限
        if (M('MenuPermission')->where(['menu_id' => ['in', $menu_ids]])->delete() === false) {
            $this->error = '删除权限失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }








}