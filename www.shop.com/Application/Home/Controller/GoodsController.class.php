<?php
namespace Home\Controller;
use Think\Controller;

/**
 * Class GoodsController
 * @package Home\Controller
 */
class GoodsController extends Controller
{
    /**
     * 将浏览量存入数据库
     * @param $id
     */
    public function clickNum($id) {

        //将浏览量存入数据库前保存到redis中
        //使用redis存储点击数
        $key = 'goods_click';
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1',6379);
        $redis = getRedis();
        $goods_click = $redis->zincrBy($key,1,$id);
        $this->ajaxReturn($goods_click);
        exit;

        //获取该商品的点击数
        $model = M('GoodsClick');
//        dump($model);exit;
        if($click_times = $model->getFieldByGoodsId($id,'click_times')){  //判断是否有记录
            ++$click_times;
            $con = ['goods_id'=>$id];
            $model->where($con)->setInc('click_times');
//            dump($rows);exit;
        }else{

            //使用mysql存储点击数
            $click_times = 1;
            $data = array(
                'goods_id'=>$id,
                'click_times'=>$click_times,
            );
            //添加到数据库
            $model->add($data);
        }
        //返回ajax数据
        $this->ajaxReturn($click_times);
    }


    /**
     * 将redis的点击数同步到数据库中
     * 1.从redis中获取所有的点击数
     * 2.遍历获取所有的键名
     * 3.删除数据库中同名的商品记录
     * 4.将redis中的存进去
     * @param type $param
     */
    public function syncClicks() {
        $redis = getRedis();
//        var_dump($redis);exit;
        $key = 'goods_click';
        $click_list = $redis->zRange($key,0,-1,true);
//        dump($click_list);exit;
        $goods_ids = array_keys($click_list);
//        dump($goods_ids);exit;
        $model = M('GoodsClick');

        $model->where(['goods_id'=>['in',$goods_ids]])->delete();
        $data = [];
        foreach($click_list as $goods_id=>$click_times){
            $data[] = [
                'goods_id'=>$goods_id,
                'click_times'=>$click_times,
            ];
        }
//        dump($data);exit;
        $model->addAll($data);
        echo '<script type="text/javascript">window.close();</script>';
    }









}