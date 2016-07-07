<?php
namespace Admin\Controller;

use Think\Controller;

/**权限
 * Class PermissionController
 * @package Admin\Controller
 */
class PermissionController extends Controller
{
    /**
     * @var \Admin\model\PermissionModel
     */
    private $_model=null;

    /**
     * 初始化执行的代码
     */
    protected function _initialize(){
        $this->_model = D('Permission');
        $meta_titles  = array(
            'index' => '管理权限',
            'add'   => '添加权限',
            'edit'  => '修改权限',
        );
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理权限';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 权限列表页面.
     * 使用了treegrid展示.
     */
    public function index(){
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 添加权限
     */
    public function add(){
        if(IS_POST){
            if($this->_model->create() === false){   //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->addPermission() === false){   //添加权限 注意父级权限
                $this->error($this->_model->getError());
            }
            $this->success('添加成功', U('index'));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * @param $id
     * 权限修改
     */
    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){  //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->savePermission() === false){  //修改保存权限，关系到层级，所以需要其他model代码处理
                $this->error($this->_model->getError());
            }
            $this->success('修改成功', U('index'));
        }else{
            //获取一条记录
            $this->assign('row', $this->_model->find($id));
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * @param $id
     * 删除权限 涉及到父子层级关系，需要单独model处理
     */
    public function delete($id){
        if($this->_model->deletePermission($id) === false){
            $this->error($this->_model->getError());
        }
        $this->success('删除成功',U('index'));
    }


    private function _before_view() {
        //读取权限列表  公用的获取列表，分布数据，展示页面
        $permissions = $this->_model->getList();
//        array_unshift($permissions);
        array_unshift($permissions, ['id'=>0,'name'=>'顶级权限','parent_id' =>null]);
        $this->assign('permissions', json_encode($permissions));
    }



}
