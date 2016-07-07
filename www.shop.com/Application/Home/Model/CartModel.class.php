<?php
namespace Home\Model;
use Think\Model;

class CartModel extends Model
{
    /**
     * @var string
     */
    protected $tableName = 'shopping_car';

    /**
     * 区分出登录和未登录的时候，取出商品的相关信息
     */
    public function getCartList_tmp() {
        //登录状态
        //未登录状态
        $cart_list   = cookie('CART');
        $data        = [];
        //取出商品的信息
        $goods_list  = [];    //保存的商品信息
        $total_price = 0;    //总计
        $goods_model = M('Goods');
        //每个商品发一次查询详细信息的sql,比较浪费资源
        foreach($cart_list as $gid => $amount){
            $goods_info              = $goods_model->find($gid);
            $goods_info['amount']    = $amount;
            $goods_info['sub_total'] = $goods_info['shop_price'] * $amount;
            $total_price+=$goods_info['sub_total'];
            $goods_list[]            = $goods_info;
        }
        $data = [
            'total_price' => $total_price,
            'goods_list'  => $goods_list
        ];
        dump($data);
    }

    /**
     * 获取当前用户的购物车详细信息.
     * @return type
     */
    public function getCartList() {
        $userinfo = login();
        //登录
        if ($userinfo) {
            $cart_list = $this->where(['member_id' => $userinfo['id']])->getField('goods_id,amount');
        } else {
            //未登录
            $cart_list = cookie('CART');
        }
        $total_price = 0;
        $goods_list  = [];
        if ($cart_list) {
            //商品id列表
            $goods_ids  = array_keys($cart_list);
            //查询出商品信息
            $goods_info_list = M('Goods')->where(['id' => ['in', $goods_ids]])->select();

            //会员价  同时获取用户积分
            if($userinfo){
                $score=M('Member')->getFieldById($userinfo['id'],'score');
//                dump($score);exit;
            }else{
                //非会员
                $score=0;

            }

            //会员信息  会员等级   bottom    score   top
            $con=array(
                'bottom'=>array('elt',$score),
                'top'=>array('egt',$score),

            );
            $level_info=M('MemberLevel')->field('id,discount')->where($con)->find();



            //组织数据,保存小计金额\总计金额\每个商品的购买数量  同时会员价格
            //判断会员价格
            foreach ($goods_info_list as $goods) {
            $member_price=M('MemberGoodsPrice')->where(['goods_id'=>$goods['id'],'member_level_id'=>$level_info['id']])->getField('price');
            if(!$member_price){
                $member_price = $level_info['discount'] / 100 * $goods['shop_price'];
            }


//                var_dump($goods);exit;
//                $goods['shop_price'] = money_format($goods['shop_price']);
                $goods['shop_price'] = money_format($member_price);
                $goods['amount']     = $cart_list[$goods['id']]; //数量
//                $goods['sub_total']  = money_format($goods['shop_price'] * $cart_list[$goods['id']]); //小计
                $goods['sub_total']  = money_format($member_price * $cart_list[$goods['id']]); //小计
                $total_price += $goods['sub_total']; //总计
                $goods_list[]        = $goods;     //所有的商品信息保存到数组中
//                dump($goods_list[]);exit;
            }
        }
        //返回多个数据,使用数组
        $data = [
            'total_price' => money_format($total_price),
            'goods_list'  => $goods_list,
        ];
//        var_dump($data);exit;
        return $data;
    }

    /**
     * 添加到购物车
     * 如果已经在购物车就加数量
     * 如果没有在购物车就加记录
     * @param integer $id 商品id
     * @param integer $amount 购买数量
     */
    public function addCart($id, $amount) {
        $userinfo = login();
        //登陆用户保存购物车数据到数据库
        if($userinfo){
            $con = [
                'memeber_id' => $userinfo['id'],
                'goods_id'   => $id,
            ];
//            $amount = $this->where($con)->getField('amount');
            if($this->where($con)->getField('amount')){
                $this->where($con)->setInc('amount', $amount);
            }else{
                $data = [
                    'member_id' => $userinfo['id'],
                    'goods_id'  => $id,
                    'amount'    => $amount,
                ];
                $this->add($data);
            }
        } else {
            //未登录用户保存购物车数据到cookie
            $key  = 'CART';
            $cart_list = cookie($key);
            if (isset($cart_list[$id])) {
                $cart_list[$id] += $amount;
            } else {
                $cart_list[$id] = $amount;
            }
            cookie($key, $cart_list, 604800);
        }
    }

    /**
     * 用户登录的时候将cookie数据同步到数据库  登录时对比用户信息，将元原来保存到cookie的数据存到数据库
     */
    public function cookie2Db() {
        //从cookie中获取购物车数据
        $key  = 'CART';
        $cart_list = cookie($key);
//        var_dump($cart_list);exit;
        //如果cookie中没有商品就无需执行后续的购物车处理
        if(!$cart_list){
            return true;
        }
        $goods_ids = array_keys($cart_list);
        //获取用户信息
        $userinfo = login();
//        dump($userinfo);exit;
        $con = [
            'member_id'=>$userinfo['id'],
            'goods_id'=>['in',$goods_ids],
        ];
        $this->where($con)->delete();
//        dump($this->getLastSql());exit;
        //将购物车数据组织成一个二维数组
        $data = [];
        foreach ($cart_list as $gid=>$amount){
            $data[]= [
                'goods_id'=>$gid,
                'amount'=>$amount,
                'member_id'=>$userinfo['id'],
            ];
        }
        $this->addAll($data);
        //把cookie中的数据销毁
        cookie($key,null);
    }

    /**
     * 清空购物车
     * @return type
     */
    public function clear(){
        $userinfo = login();
        return $this->where(['member_id'=>$userinfo['id']])->delete();
    }



}