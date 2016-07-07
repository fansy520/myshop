<?php
namespace Home\Model;

use Think\Model;

/**
 * Class OrderModel
 * @package Home\Model
 */
class OrderInfoModel extends  Model
{
    public function addOrder(){

        $user_info = login();     //获取用户信息

        //保存订单信息  地址Address，通过表中address_id获取，收货人，支付方式，送货方式，
        $address_info = D('Address')->getAddressInfo(I('post.address_id'));


        //取出address表中数据                 【$this->data == I方法】
        $this->data['name'] = $address_info['name'];
        $this->data['province_name'] = $address_info['province_name'];
        $this->data['city_name'] = $address_info['city_name'];
        $this->data['area_name'] = $address_info['area_name'];
        $this->data['detail_address'] = $address_info['detail_address'];
        $this->data['tel'] = $address_info['tel'];
        $this->data['member_id'] = $address_info['member_id'];

        //送货方式





        //保存详细信息
        //保存发票
    }

}