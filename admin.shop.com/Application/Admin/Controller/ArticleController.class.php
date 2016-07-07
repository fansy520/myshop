<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class ArticleController
 * @package Admin\Controller
 */
class ArticleController extends Controller{

    /**
     * @var \Admin\Model\ArticleModel
     * 实例化私有模型
     */
    private $_model=null;
    //构造函数
    public function _initialize(){
        $this->_model=D('Article');  //实例化模型

        // 设定标题.
        $meta_titles  = array(
            'index' => '管理文章',
            'add'   => '添加文章',
            'edit'  => '修改文章',
        );
        $meta_title= isset($meta_titles[ACTION_NAME])?$meta_titles[ACTION_NAME]:'管理文章';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 文章展示页面
     */
    public function index(){
        //搜索功能  name 字段
        $name=I('get.name');  //获取关键字 name
        $con=[];         //准备数组存放
        if($name){            //如果获取到关键字
            $con['name']=['LIKE','%'.$name.'%'];  //放入数组中
        }

        //获取页面的数据
        $rows=$this->_model->getPageResult($con);
        $article_category_model=D('ArticleCategory');
//            dump($article_category_model);exit;
        $this->assign('article_categories',$article_category_model->getList('id,name'));

        $this->assign($rows);  //分布数据
        $this->display();
    }

    /**
     * 文章添加页面
     */
    public function add(){
        if(IS_POST){  //是否POST提交
            //接收数据失败
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            //添加数据失败
            if($this->_model->addArticle() === false){
                $this->error($this->_model->getError());
            }
            //添加成功跳转
            $this->success('添加成功', U('index'));

        }else{
            //查询出 ArticleCategoryModel 中的数据
            //实例化模型  用于添加
            $article_category_model=D('ArticleCategory');
//            dump($article_category_model);exit;
            $this->assign('article_categories',$article_category_model->getList());

            //页面渲染
            $this->display();

        }
    }

    /**
     * @param $id
     * 文章编辑修改页面
     */
    public function edit($id){
        if(IS_POST){
            if($this->_model->create()===false){   //是否接受到数据
//                dump(ArticleCategoryModel->create());exit;
                $this->error($this->_model->getError());
            }
            if($this->_model->updateArticle()===false){    //是否更新成功
//                dump(ArticleCategoryModel->create());exit;
                $this->error($this->_model->getError());
            }
//            dump($this->_model->getLastSql());exit;
            $this->success('修改成功',U('index',['nocache' => NOW_TIME]));  //缓存

        }else{      //不是POST提交的方式，即将add作为edit展示页面 反之就是edit功能页面
            if(($row=$this->_model->getArticleInfo($id))===false){   //判定文章和内容是否有对应的id值
                $this->error($this->_model->getError());
            };   //根据id查询数据
            $this->assign('row',$row);       //分布数据

            //实例化模型  用于编辑显示
            $article_category_model=D('ArticleCategory');
//            dump($article_category_model);exit;
            $this->assign('article_categories',$article_category_model->getList());
            $this->display('add');           //展示页面
        }
    }

    /**
     * @param $id
     * 文章删除
     */
    public function delete($id){
        //用一个数组保存状态 更改字段名表示删除
        $data=[
            'id'=>$id,
            'status'=>0,
            'name'=>array('exp','CONCAT(`name`,"_x")'),
        ];
        if($this->_model->setField($data)){   //判断是否与$data字段一致，判断成功就更改
            $this->success('删除成功',U('index',['nocache' => NOW_TIME]));
        }else{
            $this->error($this->_model->getError());  //失败错误信息
        }
    }

}
