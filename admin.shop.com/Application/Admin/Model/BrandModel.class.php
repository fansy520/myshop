<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * Class BrandModel
 * @package Admin\Model
 * @author fansy
 */
class BrandModel extends Model
{
    protected $_validate=array(
        array('name','require','品牌名称不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('name','','品牌名称已存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
        array('sort','require','品牌排序不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('status','require','品牌状态不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
//        self::EXISTS_VALIDATE,'',self::MODEL_INSERT
    );

    /**
     * 获取分页结果
     * @param array $con
     * @return array
     */
    public function getPageResult(array $con=array()){

          //分页功能方法一
//        //获取总行数
//        $num=$this->where($con)->count();
//        //获取分页代码
//        $page=new Page($num,3);
//        //显示分页代码
//        $page_html=$page->show();
//        //按条件查询数据
//        $rows=$this->where($con)->page(I('get.p'),3)->select();
//        return array('page_html'=>$page_html,'rows'=>$rows);

        //分页方式二
        //从数据库找出状态拼接
//        $con['status']=['gt',-1];  //数据中-1表示删除，0表示隐藏 1表示显示
        $con=array_merge(array('status'=>['gt',-1]),$con);   //表示与$con合并   而$con以形参传过来的为准，上面一步省略
//        dump($con);exit;
        //获取总行数
        $count=$this->where($con)->count();
        //从配置文件 获取每页显示条数
        $size = C('PAGE_SIZE');
        //获取分页代码
        $page=new Page($count,$size);  //实例化分页对象
        $page->setConfig('theme',C('PAGE_THEME'));  //setConfig配置分页样式
        $page_html=$page->show();  //获取分页代码
        //获取分页数据
        $rows=$this->where($con)->page(I('get.p'),$size)->select();
        //返回分页代码和结果集
        return array('page_html'=>$page_html,'rows'=>$rows);

    }

    /**
     * @return mixed
     */
    public function getList(){
        return $this->where(['status'=>1])->select();
    }

}