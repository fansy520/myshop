<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;
use Admin\Service;
class GoodsCategoryModel extends Model
{
    /**
     * @var array
     */
    protected $_validate=array(
        array('name','require','商品分类名称不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('name','','商品分类名称已存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
    );

    /**
     * @return mixed
     */
    public function getList(){
        return $this->where(['status'=>1])->order('lft')->select();
    }

    /**
     * @return array
     */
    public function getPageResult(){
        //从数据库找出状态拼接
        $con=['status'=>1];  //数据中-1表示删除，0表示隐藏 1表示显示
//        $con=array_merge(array('status'=>['gt',-1]),$con);   //表示与$con合并   而$con以形参传过来的为准，上面一步省略
//        dump($con);exit;
        //获取总行数
        $num=$this->where($con)->count();
        //从配置文件 获取每页显示条数
        $size = C('PAGE_SIZE');
        //获取分页代码
        $page=new Page($num,$size);  //实例化分页对象
        $page->setConfig('theme',C('PAGE_THEME'));  //setConfig配置分页样式
        $page_html=$page->show();  //获取分页代码
        //获取分页数据
//        $rows=$this->where($con)->page(I('get.p'),$size)->select();
        $rows = $this->page(I('get.p'),$size)->where($con)->order('lft')->select();
        //返回分页代码和结果集
        return array('page_html'=>$page_html,'rows'=>$rows);
    }

    /**
     * @return bool
     */
    public function addCategory(){
        //实例化一个nestedsets所需要的数据库操作类的对象
        $orm = D('NestedSetsMysql','Logic');
        //创建一个nestedsets对象
            //  初始化的时候是这样  $orm = new \Admin\Model\NestedSetsMysql();  方便使用，需要做修改
        $nestedsets = new Service\NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        if(($cat_id = $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom'))===false){
            $this->error = M()->getError();
            return false;
        }else{
            return $cat_id;
        }
    }

    /**
     * @return bool
     */
    public function updateCategory(){
        //先获取数据表中原有的还有当前提交的  父级和原有的是否一致
        $parent_id = $this->getFieldById($this->data['id'],'parent_id');
        //在不修改层级的时候结果为false,先判断一下是否需要移动层级
        if($parent_id != $this->data['parent_id'] ){
            //实例化nestedsets所需的orm对象
            $orm = D('NestedSetsMysql','Logic');
            //创建一个nestedsets对象
            $nestedsets = new Service\NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
            if($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false){
                $this->error = '父级分类不正确';
                return false;
            }
        }
        return $this->save();
    }

    //删除
    public function deleteCategory($id){

        //实例化nestedsets所需的orm对象
        $orm = D('NestedSetsMysql','Logic');
        //创建一个对象  nestedsets
        $nestedsets = new Service\NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        return $nestedsets->delete($id);
    }


}
