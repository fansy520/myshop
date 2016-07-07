<?php
namespace Home\Model;
use Think\Model;

/**
 * Class PmConfigModel
 * @package Home\Model
 */
class PmConfigModel {

    /**
     * 获取送货方式
     * @return mixed
     */
    public function getDeliveryList(){
        return M('Delivery')->where(['status'=>1])->select();
    }

    /**
     * 获取支付方式
     * @return mixed
     */
    public function getPaymentList() {
        return M('Payment')->where(['status'=>1])->select();

    }

    /**
     * 获取配送烦事详细信息
     * @param $id
     * @return mixed
     */
    public function getDeliveryInfo($id) {
        return M('Delivery')->where(['id'=>$id])->find();
    }

    /**
     * 获取支付方式详细信息
     * @param $id
     * @return mixed
     */
    public function getPaymentInfo($id) {
        return M('Payment')->where(['id'=>$id])->find();
    }















}
