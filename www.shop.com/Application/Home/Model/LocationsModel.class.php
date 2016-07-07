<?php
namespace Home\Model;
use Think\Model;

/**
 * Class LocationsModel
 * @package Home\Model
 */
class LocationsModel extends Model{
    public function getListByParentId($parent_id=0) {
        return $this->where(['parent_id'=>$parent_id])->select();
    }
}
