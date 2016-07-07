<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
//    public function index(){
//        $this->show('<css type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</css><div css="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
//    }


    protected function _initialize(){
//        //取出分类
//        $goods_categories = M('GoodsCategory')->where(['status' => 1])->select();
//        //传入视图
//        $this->assign('goods_categories', $goods_categories);

        //登录信息
        $user_info = login();
        $this->assign('user_info', $user_info);
//exit;
        //首页才展示分类列表.
        if(ACTION_NAME == 'index'){
            $is_show_cat = true;
        }else{
            $is_show_cat = false;
        }
        $this->assign('is_show_cat', $is_show_cat);

        //不经常更新的，用S方法缓存到页面
        //由于文章分类和帮助文章并不会经常更新,所以我们可以使用数据缓存 标识是ART_CATS
        $goods_categories = S('GOODS_CAT');
        if(!$goods_categories){
            //取出分类   不是缓存的就从数据库查
            $goods_categories = M('GoodsCategory')->where(['status' => 1])->select();
            S('GOODS_CAT', $goods_categories, 300);
        }

        //文章分类，方法同商品分类
        $article_categories = S('ART_CATS');
        if(!$article_categories){
            //获取帮助文章分类     多字段需求，传入对应参数
            $article_categories = M('ArticleCategory')->where(['is_help' => 1, 'status' => 1])->getField('id,name');
            S('ART_CATS', $article_categories, 300);
        }

        //获取各个分类的文章
        $artilce_list = S('ART_LIST');    //S方法缓存
        if(!$artilce_list){
            foreach ($article_categories as $article_cat_id => $article_cat){
                $artilce_list[$article_cat_id] = M('Article')->where(['article_category_id' => $article_cat_id])->limit(6)->getField('id,id,name');
            }
            S('ART_LIST', $artilce_list, 300);
        }

        //传入视图
        $this->assign('goods_categories', $goods_categories);
        $this->assign('article_categories', $article_categories);
        $this->assign('article_list', $artilce_list);
    }

    /**
     * 分区展示商品  热销，新品，精品列表
     */
    public function index(){
        //获取新品 热销 精品列表
        $data = [
            'best_list'=>D('Goods')->getGoodsListByStatus(1),
            'new_list'=>D('Goods')->getGoodsListByStatus(2),
            'hot_list'=>D('Goods')->getGoodsListByStatus(4),
        ];
        $this->assign($data);    //分布三种状态数据到页面
        $this->display();
    }

    /**
     * 展示商品
     * @param $id
     */
    public function goods($id){
        //取出商品详情 展示
        $this->assign('row',D('Goods')->getGoodsInfo($id));
        $this->display();
    }
}