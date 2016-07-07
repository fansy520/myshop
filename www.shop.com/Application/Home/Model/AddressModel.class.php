<?php
namespace Home\Model;
use Think\Model;

/**
 * Class AddressModel
 * @package Home\Model
 */
class AddressModel extends Model{

    protected $_validate=array(
//        ['username','require','收货人不能为空',self::MUST_VALIDATE,],
        ['name','require','收货人不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
        ['province_name','require','所在省不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
        ['city_name','require','所在市不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
        ['area_name','require','所在区不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
        ['detail_address','require','详细地址不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
        ['tel','require','电话号码不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
    );
    
    /**
     * 添加地址.
     */
    public function addAddress() {
        $userinfo = login();
        unset($this->data[$this->getPk()]);
        $this->data['member_id'] = $userinfo['id'];
        //如果勾选了默认,就清空其它默认,然后再添加
        if(isset($this->data['is_default'])){
            $this->where(['member_id'=>$userinfo['id']])->setField('is_default',0);
        }
        return $this->add();
    }
    
    /**
     * 获取当前用户的所有地址.
     * @return type
     */
    public function getList() {
        $userinfo = login();
        return $this->where(['member_id'=>$userinfo['id']])->select();
    }
    
    /**
     * 获取指定id的详细信息.
     * @param integer $id
     * @return type
     */
    public function getAddressInfo($id) {
        $userinfo = login();
        return $this->where(['member_id'=>$userinfo['id'],'id'=>$id])->find();
    }
    
    /**
     * 修改收货地址.
     * @return type
     */
    public function saveAddress(){
        $userinfo = login();
        $this->data['member_id'] = $userinfo['id'];
        //如果勾选了默认,就清空其它默认,然后再添加
        if(isset($this->data['is_default'])){
            $this->where(['member_id'=>$userinfo['id']])->setField('is_default',0);
        }
        return $this->save();
    }
}
