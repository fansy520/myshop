<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 角色
 * Class RoleController
 * @package Admin\Controller
 */
class RoleController extends Controller
{
    /**
     * @var \Admin\model\RoleModel
     */
    private $_model=null;

    /**
     * 初始化执行的代码
     */
    protected function _initialize(){
        $this->_model = D('Role');
        $meta_titles  = [
            'index' => '管理角色',
            'add'   => '添加角色',
            'edit'  => '修改角色',
        ];
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理角色';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 角色页面展示
     */
    public function index(){
        //获取数据，分不到页面
        $this->assign('rows',$this->_model->getList());
        $this->display();

    }

    /**
     * 添加角色
     */
    public function add(){
        if(IS_POST){
            if($this->_model->create() === false){       //收集数据
                $this->error($this->_model->getError());
            }
            if($this->_model->addRole() === false){      //添加数据  注意角色与权限的关联
                $this->error($this->_model->getError());
            }
            //添加成功 提示跳转
            $this->success('添加成功', U('index'));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 编辑修改角色
     */
    public function edit($id){
        if(IS_POST){     //判断是否POST方式提交
            if($this->_model->create() === false){       //收集数据
                $this->error($this->_model->getError());
            }
            if($this->_model->updateRole() === false){     //添加数据
                $this->error($this->_model->getError());
            }
            // 修改成功 提示跳转
            $this->success('修改成功', U('index',['nocache'=>NOW_TIME]));
        }else{    //不是POST方式提交就为回显数据
            //获取到当前记录
            $row = $this->_model->getRoleInfo($id);
            $this->_before_view();       //获取所有的角色
            $this->assign('row',$row);  //分配数据
            $this->display('add');       //渲染视图
        }
    }

    /**删除角色  物理删除
     * @param $id
     */
    public function delete($id){
        if($this->_model->deleteRole($id)===false){
            $this->error($this->_model->getError());
        }else{
            $this->success('删除成功', U('index',['nocache'=>NOW_TIME]));
        }
    }

    //添加和编辑页面的公用方法  优化
    private function _before_view(){
//        $this->assign('brands',D('Brand')->getList());    //加载商品品牌列表
        $this->assign('Permissions', json_encode(D('Permission')->getList()));   //加载权限列表
//        var_dump($rows);exit;
    }

}