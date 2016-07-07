<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller{
    /**
     * 页面展示
     */
    public function index(){
        $this->display();
    }

    /**
     * 框架集权限展示
     */
    public  function top(){
        $this->display();
    }

    public function main(){
        $this->display();
    }

    /**
     * 菜单展示  需根据权限展示
     */
    public function menu(){
        //实例化对象 找到菜单数据
        $menuModel=D('Menu');
        //获取数据
        $menus=$menuModel->getMenus();
        //分布数据
        $this->assign('menus',$menus);
        $this->display();
    }

}