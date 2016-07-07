<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

/**
 * Class SupplierController
 * @package Admin\Controller
 */
class SupplierController extends Controller
{
    /**
     * @var \Admin\Model\SupplierModel
     */
    private $_model=null;

    //实例化模型私有化
    protected function _initialize(){
        //初始化控制器存放到一个属性中
        $this->_model=D('Supplier');
        // 设定标题.
        $meta_titles  = array(
            'index' => '管理供货商',
            'add'   => '添加供货商',
            'edit'  => '修改供货商',
        );
        $meta_title= isset($meta_titles[ACTION_NAME])?$meta_titles[ACTION_NAME]:'管理供货商';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 列表页面
     */
    public function index(){
        //实例化模型

        $name = I('get.name');  //获取搜索的关键字name  获取查询条件,并传递给模型
//        $intro=I('get.intro');
        $con = [];              //准备数组存放
        if ($name) {            //如果查询到关键字
            $con['name'] = ['LIKE','%'.$name.'%'];  //放入数组
//            $con['intro'] = ['LIKE','%'.$intro.'%'];  //放入数组
        }
        $rows=$this->_model->getPageResult($con);  //查询数据

        $this->assign($rows);  //分布数据显示  从model里接收到的数据，每一个数据都是一个变量
        $this->display();      //页面渲染
    }

    /**
     * 添加供应商
     */
    public function add(){
        if(IS_POST){
//            $model=D('Supplier');  //实例化模型
//            $res=$model->create();  //接收数据
//            dump($res);exit;
//            $row=$model->add();  //添加接收到的数据

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
        }
        //页面渲染
        $this->display();

    }

    /**
     * 修改供应商
     * @param $id 要修改的供应商id
     */
    public function edit($id){
        if (IS_POST) {
            //实例化对象
            //实例化对象 收集数据  一步到位
            if($this->_model->create() === false){  //接收数据失败
                $this->error($this->_model->getError());
            }
            //执行添加操作
            if($this->_model->save() === false){    //添加保存数据失败
                $this->error($this->_model->getError());
            }
            //成功跳转
            $this->success('修改成功', U('index',['nocache' => NOW_TIME]));
        } else {
            //读取数据
            $row = $this->_model->find($id);
            //分配数据到视图
            $this->assign('row', $row);
            //渲染视图
            $this->display('add');
        }
    }

    /**
     * @param $id 删除一条供货商信息
     * 1.从数据库删除  物理删除
     * 2.改变显示方式  逻辑删除
     */
    public function delete($id){

        //将条件下的字段名status状态设置为0
//        $this->_model->where(array('id'=>$id))->setField('status',0);

        //用一个数组保存状态 更改字段名表示删除
        $data=array(
            'id'=>$id,
            'status'=>0,
            'name'=>array('exp','concat(`name`,"_d")'),
        );

        if($this->_model->setField($data)){
//            dump($this->_model->setField($data));exit;
//              dump($this->_model->getLastSql());exit;
            $this->success('删除成功',U('index',['nocache' => NOW_TIME]));
        }else{
            $this->error($this->_model->getError());
        }

    }

}