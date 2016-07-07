<?php
namespace Home\Controller;

//use Home\Model\PmConfigModel;
use Think\Controller;

class CartController extends Controller
{
    /**
     * @var \Home\Model\CartModel
     */
    private $_model = null;

    /**
     * 构造函数
     */
    public function _initialize(){
        $this->assign('action_name', ACTION_NAME);
        $this->_model = D('Cart');
    }

    /**
     * 将商品放入购物车  保存模式为数组 【3=>2】
     * @param $id
     * @param $amount
     */
    public function addCart($id,$amount){
        //没有登录的时候,没有username,存入cookie中,这是的数据保存只需要保存每个要买商品的id和数量
//        $key = 'CART';   //将键名作为保存条件
//        $cart_list = cookie($key);         //保存到cookie中
//        if(isset($cart_list[$id])){        //检查是否存在
//            $cart_list[$id] += $amount;    //累计
//        }else{
//            $cart_list[$id] = $amount;
//        }
//        cookie($key, $cart_list,604800);   //cookie保存内容和时间

        $this->_model->addCart($id, $amount);
//        dump($rows);exit;
        //添加完成后就跳转,避免重复添加
        $this->success('添加成功',U('flow1'));
    }

    /**
     * 第一级信息  收货地址
     */
    public function flow1(){
        $this->assign($this->_model->getCartList());
        $this->display();
    }

    /**
     * 第二级信息
     */
    public function flow2(){
        if(!login()){
            cookie('_self_',__SELF__);    //用self方法，在登录前的购物车信息保存到cookie中
//            var_dump(cookie('_self_',__SELF__));exit;
            $this->error('请先登录',U('Member/login'));
//        }else{
//            //收货地址
//            $this->assign('address_list', D('Address')->getList());
//            $this->display();
//        }

        }else{
            //收货地址
            $this->assign('address_list', D('Address')->getList());

            //送货方式
//            $pmconfig_model = new PmConfigModel();
            $pmconfig_model = new \Home\Model\PmConfigModel();
//            dump($pmconfig_model);exit;
            //分布数据
            $this->assign('deliveries', $pmconfig_model->getDeliveryList());
//dump($pmconfig_model->getDeliveryList());exit;
            //支付方式
            $this->assign('payments', $pmconfig_model->getPaymentList());

            //计算，获取购物车列表
            $this->assign($this->_model->getCartList());
            $this->display();


        }




    }













}