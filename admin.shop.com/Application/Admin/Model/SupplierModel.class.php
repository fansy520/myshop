<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * Class SupplierModel
 * @package Admin\Model
 */
class SupplierModel extends Model
{
    protected $_validate=array(
        array('name','require','供货商名称不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('sort','require','供货商排序不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('status','require','供货商状态不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
//        self::EXISTS_VALIDATE,'',self::MODEL_INSERT
    );

    /**
     * @param array $con
     * @return array
     */
    public function getPageResult(array $con=array()){

        //获取总行数
        $num=$this->where($con)->count();
        //获取分页代码
        $page=new Page($num,5);
        //显示分页代码
        $page_html=$page->show();
        //按条件查询数据
        $rows=$this->where($con)->page(I('get.p'),5)->select();
        return array('page_html'=>$page_html,'rows'=>$rows);

    }

    /**
     * @return mixed
     */
    public function getList(){
        return $this->where(['status'=>1])->select();
    }



}