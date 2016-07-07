<?php
namespace Admin\Controller;

use Think\Controller;

/**
* 菜单和权限的关联模块
* 某个菜单[添加商品] 只有权限为5的才能够看到
* 我们要将 添加商品菜单  id=>权限【值】  保存到数据表中
 * Class MenuController
 * @package Admin\Controller
 */
class MenuController extends Controller
{
    /**
     * @var \Admin\Model\MenuModel
     */
    private $_model = null;

    /**
     * 初始化控制器的时候自动执行的代码.
     */
    protected function _initialize(){
        $this->_model = D('Menu');
        $meta_titles  = [
            'index' => '管理菜单',
            'add'   => '添加菜单',
            'edit'  => '修改菜单',
        ];
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理菜单';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 菜单列表展示
     */
    public function index(){
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 菜单添加
     */
    public function add(){
        if(IS_POST){
            if($this->_model->create() === false){   //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->addMenu() === false){   //添加管理员  注意权限，角色的关联  还有一个额外权限
                $this->error($this->_model->getError());
            }
            $this->success('添加成功', U('index',['nocache'=>NOW_TIME]));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**修改菜单
     * @param $id
     */
    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){  //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->saveMenu() === false){  //修改保存权限，关系到层级，所以需要其他model代码处理
                $this->error($this->_model->getError());
            }
            $this->success('修改成功', U('index',['nocache'=>NOW_TIME]));
        }else{
            //回显时，需要将关联的权限，角色都回显
//            $this->assign('row', $this->_model->find($id));
            $row = $this->_model->getMenuInfo($id);
            $this->_before_view();   //获取所有的菜单
            $this->assign('row', $row);
            $this->display('add');
        }

    }


    /**删除菜单
     * @param $id
     */
    public function delete($id){
        if($this->_model->deleteMenu($id) === false){
            $this->error($this->_model->getError());
        }
        $this->success('删除成功',U('index'));
    }


    /**
     * 添加和编辑页面的通用操作
     */
    private function _before_view() {
        //获取所有的已有菜单
        $goods_categories = $this->_model->getList();
        //添加一个顶级菜单选项
        array_unshift($goods_categories, ['id'=>0,'name'=>'顶级菜单','parent_id'=>null]);
        $this->assign('goods_categories', json_encode($goods_categories));

        //读出所有的权限
        $permissions = D('Permission')->getList();
        $this->assign('permissions', json_encode($permissions));
    }


}