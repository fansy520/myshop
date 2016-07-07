<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * Class GoodsModel
 * @package Admin\Model
 */
class GoodsModel extends Model{

    //商品的一些状态可以建表保存，但是比较麻烦  可以将商品状态等设置为公有属性
    /**
     * 商品促销
     * @var type
     */
    public $goods_status=array(
        1=>'精品',
        2=>'新品',
        4=>'热销',
    );

    /**
     * 商品售卖状态.
     * @var type
     */
    public $is_on_sales = array(
        1=>'上架',
        0=>'下架',
);

    /**自动验证功能  字段
     * @var array
     */
    protected $_validate = array(
        array('name', 'require', '商品名不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
        array('goods_category_id', 'require', '商品分类不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
        array('brand_id', 'require', '商品品牌不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
        array('supplier_id', 'require', '供货商名称不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
//        array('status', 'require', '商品状态不能为空', self::MUST_VALIDATE, '', self::MODEL_BOTH),
        array('shop_price', 'currency', '本店售价不合法', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
        array('market_price', 'currency', '市场价不合法', self::EXISTS_VALIDATE, '', self::MODEL_BOTH),
    );

    /**
     * 自动完成的功能  添加的时间
     * @var array
     */
    protected $_auto=array(
        array('inputtime',NOW_TIME,self::MODEL_INSERT),
        array('goods_status','array_sum',self::MODEL_BOTH,'function'),
        array('sn','create_sn',self::MODEL_BOTH,'callback'),  //create_sn方法用的不多，就在这里封装就好
    );

    /**
     * 货号的保存 按规则保存 没有就自动生成
     * 规则:SN年月日(6位数字)
     * @param string $sn
     * @return string
     */
    protected function create_sn($sn){
        if($sn){
            return $sn;
        }
        //获取数量
        $goods_num_model=M('GoodsNums');  //实例化数据
        $date=date('Ymd');  //准备数据格式 时间
        $num=$goods_num_model->getFieldByDate($date,'num');
//        dump($num);exit;
        if($num){  //判断是否有这个数据
            $num++;  //有就添加一条
            $goods_num_model->setField(array('date'=>$date,'num'=>$num)); //保存数据到数据库
        }else{
            $num=1;
            $goods_num_model->add(array('date'=>$date,'num'=>$num));  //保存数据到数据库
        }
        //生成货号编号格式
//        $sn='SN'.$date.$num;
        $sn='SN'.$date.str_pad($num,6,'0',STR_PAD_LEFT);
        return $sn;
    }

    /**
     * 获取分页信息
     * @param array $con
     * @return array
     */
    public function getPageResult(array $con = []) {
        $con = array_merge(['status' =>['gt',-1]], $con);
        //分页列表
        $count = $this->where($con)->count();
        $size = C('PAGE_SIZE');
        $page  = new Page($count, $size);
        $page->setConfig('theme', C('PAGE_THEME'));
        $page_html = $page->show();
        //获取当页数据
        $rows = $this->where($con)->page(I('get.p'), $size)->select();
        foreach($rows as $key => $value){
            $value['is_best'] = $value['goods_status'] & 1 ? 1 : 0;
            $value['is_new']  = $value['goods_status'] & 2 ? 1 : 0;
            $value['is_hot']  = $value['goods_status'] & 4 ? 1 : 0;
            $rows[$key] = $value;
        }
        return ['rows' => $rows, 'page_html' => $page_html];
    }

    /** 添加商品
     *  保存详细信息
     *  保存相册
     * @return bool
     */
    public function addGoods(){
//        dump($this->data());exit;
//        if(($goods_id= $this->add())===false){
//            return false;
//        };
        unset($this->data[$this->getPk()]);
        $this->startTrans();
        //添加商品的基本信息
        if(($goods_id = $this->_saveGoodsInfo()) === false){
            $this->rollback();
            return false;
        }

        //保存商品详细描述
        if($this->_saveGoodsIntro($goods_id) === false){
            $this->rollback();
            return false;
        }

        //保存商品相册
        if($this->_saveGoodsGallery($goods_id) === false){
            $this->rollback();
            return false;
        }

        $this->commit();
        return true;
    }

    /**保存信息  可执行修改，添加   $is_new = true 调用add否则save
     * @param bool|true $is_new
     * @return bool|mixed
     */
    private function _saveGoodsInfo($is_new = true){
        if($is_new){  //判断
            if(($goods_id = $this->add()) === false){
                return false;
            }
        }else{
            if(($goods_id = $this->save()) === false){
                return false;
            }
        }
        return $goods_id;
    }

    /**
     * 保存详细信息
     * @param integer $goods_id
     * @param bool|true $is_new
     * @return bool
     */
    private function _saveGoodsIntro($goods_id, $is_new = true){
        $content_model = M('GoodsIntro');  //实例化详细信息对象，M方法直接使用GoodsIntro表
        $data = array(
            'goods_id' => $goods_id,
            'content' => I('post.content', '', false),
        );
        if($is_new){
            if($content_model->add($data) === false){  //判断调用详细内容content添加是否成功
                $this->error = $content_model->getError();
                return false;
            }
        }else{
            if($content_model->save($data) === false){  //不是添加即为修改 save方法保存
                $this->error = $content_model->getError();
                return false;
            }
        }
        return true;
    }

    /**保存相册
     * @param $goods_id  对应的商品id
     * @return bool
     */
    private function _saveGoodsGallery($goods_id){
        $gallery_model = M('GoodsGallery');  //实例化相册对象
        $paths = I('post.path');
        $data = array();
        foreach ($paths as $path) {
            $data[] = array(
                'goods_id' => $goods_id,
                'path' => $path,
            );
        }
        if($data && $gallery_model->addAll($data) === false){
            $this->error = $gallery_model->getError();
            return false;
        }
        return true;
    }

    /**回显数据
     * @param $id 对应的商品id
     * @return mixed
     */
    public function getGoodsInfo($id) {
        //获取数据信息
        $row  = $this->alias('g')->join('__GOODS_INTRO__ AS gintro ON gintro.goods_id=g.id')->find($id);
        //实例化相册对象
        $gallery_model = M('GoodsGallery');
        $row['paths'] = $gallery_model->where(['goods_id' => $id])->getField('id,path', true);
        return $row;
    }

    /**
     * @return bool
     * 修改商品 edit
     */
    public function updateGoods() {
        $this->startTrans();  //开启事务
        $request_data = $this->data;  //转换
        //保存基本信息  false
        if($this->_saveGoodsInfo(false) === false){  //判断是否保存成功
            $this->rollback();  //保存失败就回滚
            return false;
        }
        //保存详细描述  保存信息到另一张表
        if($this->_saveGoodsIntro($request_data['id'],false) === false){  //判断保存信息到另一张表
            $this->rollback();
            return false;
        }
        //保存相册信息
        if($this->_saveGoodsGallery($request_data['id']) === false){
            $this->rollback();
            return false;
        }

        //保存会员价格
        $member_prices = I('post.member_price');
        //会员价格已经存在，将原来的删除
        $member_price_model = M('MemberGoodsPrice');
//        dump($member_price_model);exit;
        $member_price_model->where(['goods_id'=>$request_data['id']])->delete();
        $data = [];
        foreach($member_prices as $level=>$price){
            if(empty($price)){
                continue;
            }
            $data[] = [
                'goods_id'=>$request_data['id'],
                'member_level_id'=>$level,
                'price'=>$price,
            ];
        }

        //保存删除后的会员价格
        if($data){
            $member_price_model->addAll($data);
        }

        $this->commit();
        return true;
    }


}