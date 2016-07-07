<?php
namespace Home\Model;
use Think\Model;

/**
 * Class GoodsModel
 * @package Home\Model
 */
class GoodsModel extends Model{
    /**
     * @param $status
     * @return mixed
     */
    public function getGoodsListByStatus($status){
        //通过status获取商品状态
        $con[] = 'goods_status&' . $status;
        $con['is_on_sale'] = 1;
        $con['status'] = 1;
        return $this->where($con)->select();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getGoodsInfo($id){
        //多表查询，获取商品信息，商品详细描述，商品相册
        $row = $this->field('g.*,gi.content,b.name as bname')
            ->alias('g')
            ->join('__GOODS_INTRO__ AS gi ON gi.goods_id=g.id')
            ->join('__BRAND__ as b on g.brand_id=b.id')
            ->where(['g.id'=>$id])->find();
        //取出相册数据  对应的id和路劲
        $gallery_model = M('GoodsGallery');
//        $row['paths']  = $gallery_model->where(['goods_id' => $id])->getField('id,path', true);
        $row['paths']  = $gallery_model->where(['goods_id' => $id])->getField('path', true);

        //会员价格  会员id对应的价格
        $member_price_list = M('MemberGoodsPrice')->where(['goods_id'=>$id])->getField('member_level_id,price');
//        dump($this->getLastSql())exit;
//        dump($member_price_list)exit;
        //获取会员折扣率
        $discount_list = M('MemberLevel')->where(['status'=>1])->getField('id,name,discount');
        //会员价格列表
        $list = [];
        foreach($discount_list as $level_id=>$level_info){
            //会员级别对应的价格
            //如果为此商品的会员单独设置了价格,就优先使用
            if(isset($member_price_list[$level_id])){
                $list[] = [
                    'name'=>$level_info['name'],
                    'price'=>$member_price_list[$level_id],   //取对应的id
                ];
            }else{
            //如果没有单独配置,就是用通用的折扣
                $list[] = [
                    'name'=>$level_info['name'],
                    'price'=>$row['shop_price'] * $level_info['discount'] / 100,  //没有则按正常价格计算
                ];
            }
        }
        //将各种结果保存
        $row['member_price_list'] = $list;
        //返回这个值
        return $row;
    }











}