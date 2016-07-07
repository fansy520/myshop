<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
/**
 * 商品goods
 * Class GoodsController
 * @package Admin\Controller
 */
class GoodsController extends Controller{

    /**
     * @var \Admin\model\GoodsModel
     */
    private $_model=null;

    /**
     * 初始化执行的代码
     */
    protected function _initialize(){
        $this->_model = D('Goods');
        $meta_titles  = [
            'index' => '管理商品',
            'add'   => '添加商品',
            'edit'  => '修改商品',
        ];
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理商品';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 列表展示页面
     */
    public function index(){
//            //搜索功能  name 字段
//            $name=I('get.name');  //获取关键字 name
//            $con=[];         //准备数组存放
//                if($name){            //如果获取到关键字
//                    $con['name']=['LIKE','%'.$name.'%'];  //放入数组中
//                }

    //下拉框搜索页面
        //根据条件查询
        $con=array();   //避免多次查询时数据丢失，可以先做保存
        $goods_category_id = I('get.goods_category_id');     //I方法获取分类id
        if ($goods_category_id){
            $con['goods_category_id'] = $goods_category_id;  //数组中的id就是输入框传过来的id
        }
        $brand_id = I('get.brand_id');                      //I方法获取分类id
        if($brand_id){
            $con['brand_id'] = $brand_id;                  //数组中的id就是输入框传过来的id
        }
        $is_on_sale = I('get.is_on_sale');                 //I方法获取分类id
        if(strlen($is_on_sale)){              //判断传的值有内容
            $con['is_on_sale'] = $is_on_sale;
        }

        $keyword = I('get.keyword');
        if($keyword){
            $con['name'] = ['like','%'.$keyword.'%'];    //coreseek  模糊查询
        }

        //商品推荐的搜索  字段值不一样
        $goods_status = I('get.goods_status');
        if($goods_status){
            $con[] = 'goods_status & ' .$goods_status;
        }

        $this->assign('goods_categories',D('GoodsCategory')->getList());  //商品分类
        $this->assign('brands',D('Brand')->getList());                    //品牌
        $this->assign('goods_statuses', $this->_model->goods_status);   //商品促销
        $this->assign('is_on_sales', $this->_model->is_on_sales);         //商品状态
        $this->assign($this->_model->getPageResult($con));
        $this->display();
    }

    /**
     * 添加商品  addGoods 方法
     */
    public function add(){
//        echo 111;exit;
//        if(IS_POST){
////            dump($this->_model->getLastSql());exit;
//            if($this->_model->create()===false){  //获取数据判断
//
////                dump($this->_model->create()===false);exit;
//                $this->error($this->_model->getError());
//            }
//            if($this->_model->addGoods()===false){  //添加数据判断
//                $this->error($this->_model->getError());
//            }
//            $this->success('添加成功',U('index',['nocache'=>NOW_TIME]));
//        }else{
//            //需要在添加页面加载品牌列表brand  在brandModel里写getList方法
//            $this->assign('brands',D('Brand')->getList());  //商品品牌数据
//            $this->assign('suppliers',D('Supplier')->getList());  //供货商数据
//            $this->assign('goods_categories',json_encode(D('GoodsCategory')->getList())); //商品分类数据
////        var_dump($rows);exit;
//            $this->display();
//        }
        if(IS_POST){
//            echo 111;exit;
            if($this->_model->create() === false){  //获取数据
//                dump($this->_model->getLastSql());exit;
                $this->error($this->_model->getError());
            }
            if($this->_model->addGoods() === false){  //添加数据
                $this->error($this->_model->getError());
            }
            //跳转成功
            $this->success('添加成功', U('index',['nocache' => NOW_TIME]));
        }else{
            //
            $this->_before_view();
            $this->display();
        }

    }

    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){  //接收数据
                $this->error($this->_model->getError());
            }
            if($this->_model->updateGoods() === false){  //更新数据
                $this->error($this->_model->getError());
            }
            $this->success('修改成功', U('index',['nocache' => NOW_TIME]));
        }else{
            $row = $this->_model->getGoodsInfo($id);  //回显信息
            $this->assign('row', $row);  //分布数据
            //调用自定义方法
            $this->_before_view();
            $this->display('add');
        }
    }

    public function delete($id){
        //用一个数组保存状态 更改字段名表示删除
        $data=[
            'id'=>$id,
            'status'=>0,
            'name'=>array('exp','CONCAT(`name`,"_x")'),
        ];
        if($this->_model->setField($data)===false){
            $this->error($this->_model->getError());
        }else{
            $this->success('删除成功', U('index',['nocache' => NOW_TIME]));
        }
    }


    //添加和编辑页面的公用方法  优化
    private function _before_view(){
        $this->assign('brands',D('Brand')->getList());    //加载商品品牌列表
        $this->assign('suppliers',D('Supplier')->getList());  //加载商品供货商列表
        $this->assign('goods_categories', json_encode(D('GoodsCategory')->getList()));   //加载商品分类列表
        //获取会员级别列表
        $this->assign('member_levels',M('MemberLevel')->where(['status'=>1])->select());
    }



}