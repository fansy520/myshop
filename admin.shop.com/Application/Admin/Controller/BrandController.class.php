<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Page;
/**
 * Class BrandController
 * @package Admin\Controller
 */
class BrandController extends Controller
{
    /**
     * @var \Admin\Model\BrandModel
     * 将实例化模型私有化   模板继承私有化
     */
    private $_model=null;

    //构造函数
    public function _initialize(){
        $this->_model=D('Brand');   //实例化模型

        // 设定标题.
        $meta_titles  = array(
            'index' => '管理品牌',
            'add'   => '添加品牌',
            'edit'  => '修改品牌',
        );
        $meta_title= isset($meta_titles[ACTION_NAME])?$meta_titles[ACTION_NAME]:'管理品牌';
        $this->assign('meta_title', $meta_title);
    }

    //品牌展示页面
    public function index(){
//        $brandModel=D('Brand');

        //搜索功能  name 字段
        $name=I('get.name');  //获取关键字 name
        $con=[];         //准备数组存放
        if($name){            //如果获取到关键字
            $con['name']=['LIKE','%'.$name.'%'];  //放入数组中
        }
//        $rows=$this->_model->select();
        //将页面的分页和select功能一起使用

        $rows=$this->_model->getPageResult($con);
        $this->assign($rows);
        $this->display();
    }

    //品牌添加页面
    public function add(){
        if(IS_POST){  //是否POST提交
            //实例化模型
//            $brandModel=D('Brand');
//            $res=$brandModel->create();
//            $rows=$brandModel->add();
            //接收数据失败
            if($this->_model->create()===false){
                $this->error($this->_model->getError());
            }
            //添加数据失败
            if($this->_model->add()===false){
                $this->error($this->_model->getError());
            }
            //添加成功跳转
            $this->success('添加成功',U('index'));

        }else{
        //页面渲染
        $this->display();
        }
    }

    //品牌编辑页面
    public function edit($id){
        if(IS_POST){

            if($this->_model->create()===false){   //是否接受到数据
                $this->error($this->_model->getError());
            }
            if($this->_model->save()===false){    //是否更新成功

                $this->error($this->_model->getError());
            }
//            dump($this->_model->getLastSql());exit;
                $this->success('修改成功',U('index',['nocache'=>NOW_TIME]));  //缓存

        }else{      //不是POST提交的方式，即将add作为edit展示页面 反之就是edit功能页面
            $row=$this->_model->find($id);   //根据id查询数据
            $this->assign('row',$row);       //分布数据
            $this->display('add');           //展示add页面
        }
    }

    //删除页面
    public function delete($id){
            //将条件下的字段名status状态设置为0
//            $this->_model->where(array('id'=>$id))->setField('status',0);

        //用一个数组保存状态 更改字段名表示删除
        $data=[
            'id'=>$id,
            'status'=>0,
            'name'=>array('exp','CONCAT(`name`,"_x")'),
        ];

        if($this->_model->setField($data)){   //判断是否与$data字段一致，判断成功就更改
            $this->success('删除成功',U('index',['nocache' => NOW_TIME]));
        }else{
            $this->error($this->_model->getError());  //失败错误信息
        }
    }
}