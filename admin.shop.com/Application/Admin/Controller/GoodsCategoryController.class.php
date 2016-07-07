<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Page;
/**
 * Class GoodsCategoryController
 * @package Admin\Controller
 */
class GoodsCategoryController extends Controller
{
    /**
     * @var \Admin\Model\GoodsCategoryModel
     */
    private $_model = null;

    /**
     * 构造函数自动执行的代码.
     */
    protected function _initialize(){
        $this->_model = D('GoodsCategory');
        $meta_titles = [
            'index' => '管理商品分类',
            'add' => '添加商品分类',
            'edit' => '修改商品分类',
        ];
        $meta_title = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理商品分类';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 展示页面
     */
    public function index(){
        //搜索功能  name 字段
//        $name=I('get.name');  //获取关键字 name
//        $con=[];         //准备数组存放
//        if($name){            //如果获取到关键字
//            $con['name']=['LIKE','%'.$name.'%'];  //放入数组中
//        }
        $rows=$this->_model->getPageResult();
        $this->assign($rows);
        $this->display();
    }

    //添加功能
    public function add(){
        //获取页面的数据
        if(IS_POST){
////        $rows=$this->_model->getPageResult($con);
//        $article_category_model=D('ArticleCategory');
////            dump($article_category_model);exit;
            //收集数据
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            //添加数据
            if($this->_model->addCategory() === false){
                $this->error($this->_model->getError());
            }
            //提示跳转
            $this->success('添加成功',U('index',['nocache'=>NOW_TIME]));
        }else{
            //获取所有的已有分类
//            array_unshift($goods_categories,array('id'=>0,'name'=>'顶级分类','parent_id'=>null));
            $this->assign('goods_categories', json_encode($this->_model->getList()));
            $this->_before_view();

            $this->display();
        }
    }

    //编辑
    public function edit($id){
//        echo 111;
//        dump(IS_POST);dump($id);exit;
        if(IS_POST){   //是否POST提交
//            dump(IS_POST);dump($id);exit;
//            $res=$this->_model->create();
            if($this->_model->create() === false){  //判断是否接收到数据
                $this->error($this->_model->getError());
            }
            //添加数据
            if($this->_model->updateCategory() === false){   //判断是更新成功
                $this->error($this->_model->getError());
            }
            //提示跳转
            $this->success('修改成功', U('index',['nocache'=>NOW_TIME]));
        }else{
            //获取到当前记录
            $row = $this->_model->find($id);
            //获取所有的分类
            $this->_before_view();
            //分配数据到页面
            $this->assign('row',$row);
            //调用视图
            $this->display('add');
        }
    }

   //删除    直接使用物理删除
    public function delete($id){
        if($this->_model->deleteCategory($id)){
            $this->success('删除成功',U('index',['nocache' => NOW_TIME]));
        }else{
            $this->error($this->_model->getError());  //失败错误信息
        }
    }

    //添加和编辑页面的公用方法  优化
    private function _before_view(){
        //获取所有的已有分类
        $goods_categories = $this->_model->getList();
        //添加一个顶级分类选项
        array_unshift($goods_categories,array('id'=>0,'name'=>'顶级分类','parent_id'=>null));
        $this->assign('goods_categories', json_encode($goods_categories));
    }


}
