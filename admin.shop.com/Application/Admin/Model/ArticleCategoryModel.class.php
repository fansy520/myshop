<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Class SupplierModel
 * @package Admin\Model
 */
class ArticleCategoryModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '文章分类名称不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH),
        array('name', '', '文章分类名称不能为空', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('sort', 'require', '文章分类排序不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH),
        array('status', 'require', '文章分类状态不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH),
        array('is_help', 'require', '文章分类是否需要帮助不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH),
//        self::EXISTS_VALIDATE,'',self::MODEL_INSERT
    );

    public function getPageResult(array $con = array())
    {

        //从数据库找出状态拼接
//        $con['status']=['gt',-1];  //数据中-1表示删除不显示，0表示删除 1表示显示
        $con = array_merge(array('status' => ['gt', -1]), $con);   //表示与$con合并   而$con以形参传过来的为准，上面一步省略
//        $cond=array_merge(array('is_help'=>['gt',-1]),$cond);
        //获取总行数
        $num = $this->where($con)->count();
//        $num=$this->where($con,$cond)->count();
        //从配置文件 获取每页显示条数
        $size = C('PAGE_SIZE');
        //获取分页代码
        $page = new Page($num, $size);  //实例化分页对象
        $page->setConfig('theme', C('PAGE_THEME'));  //setConfig配置分页样式
        $page_html = $page->show();  //获取分页代码
        //获取分页数据
        $rows = $this->where($con)->page(I('get.p'), $size)->select();
//        $rows=$this->where($con,$cond)->page(I('get.p'),$size)->select();
        //返回分页代码和结果集
        return array('page_html' => $page_html, 'rows' => $rows);

    }


//读取ArticleCategoryModel 中的内容  所有的可用的值为1的状态数据
    /**
     * @return array
     */
    public function getList($field='*'){
//        return $this->where(array('status'=>1))->select();  //查询出所有 当需要编辑回显时分类只显示一条 需要改写
    if($field=='*'){
        return $this->where(['status' =>1])->select();   //不拼接查询所有
    }else{
        return $this->where(['status' =>1])->getField($field);  //查询吹对应id的字段  //动态查询
    }

    }

}