<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * Class ArticleModel
 * @package Admin\Model
 */
class ArticleModel extends Model
{
    protected $_validate=array(
        array('name','require','文章名称不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('name','','文章名称不能重复',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
        array('article_category_id','require','文章分类不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('sort','require','文章排序不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
        array('status','require','文章显示状态不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH),
//        self::EXISTS_VALIDATE,'',self::MODEL_INSERT
    );

    /**添加时间自动完成
     * @var array
     */
    protected $_auto=array(
        ['inputtime',NOW_TIME,self::MODEL_INSERT]
    );

    public function getPageResult(array $con=array()){

        //从数据库找出状态拼接
//        $con['status']=['gt',-1];  //数据中-1表示删除不显示，0表示删除 1表示显示
        $con=array_merge(array('status'=>['gt',-1]),$con);   //表示与$con合并   而$con以形参传过来的为准，上面一步省略
//        $cond=array_merge(array('is_help'=>['gt',-1]),$cond);
        //获取总行数
        $num=$this->where($con)->count();
//        $num=$this->where($con,$cond)->count();
        //从配置文件 获取每页显示条数
        $size = C('PAGE_SIZE');
        //获取分页代码
        $page=new Page($num,$size);  //实例化分页对象
        $page->setConfig('theme',C('PAGE_THEME'));  //setConfig配置分页样式
        $page_html=$page->show();  //获取分页代码
        //获取分页数据
        $rows=$this->where($con)->page(I('get.p'),$size)->select();
//        $rows=$this->where($con,$cond)->page(I('get.p'),$size)->select();
        //返回分页代码和结果集
        return array('page_html'=>$page_html,'rows'=>$rows);

    }

    //添加数据需要添加到多张表，不能直接使用$this_model->add()方法了
    /**
     * @return bool
     */
    public function addArticle(){
        if(($article_id=$this->add())===false){  //判断执行条件
            return false;
        }

        //实例化文章添加内容的模型
        $article_content_model=M('ArticleContent');
        //准备数组装内容
        $data=array(
            'article_id'=>$article_id,
            'content'=>I('post.content'),
        );
//        dump($data);exit;
        if($article_content_model->add($data)===false){
            $this->error=$article_content_model->getError();
            return false;
        }
        return true;
    }

    //联合查询，用于edit编辑回显 显示详细内容
    /**
     * @param $id
     * @return bool
     */
    public function getArticleInfo($id){
        //    select * from article as ae inner join article_content as act on ae.article_category_id=act.id


//        $row=$this->field('ae.id,name,intro,....')->alias('ae')->join('article_content as act ON ae.id=act.article_id')->find($id);
//        return $this->alias('ae')->join('LEFT JOIN__ARTICLE_CONTENT__ as act ON ae.id=act.article_id')->find($id);
        $row=$this->alias('ae')->join('__ARTICLE_CONTENT__ as act ON ae.id=act.article_id')->find($id);
//        dump($row);exit;
        if(empty($row)){
            $this->error='文章不存在，请仔细检查';
            return false;
        }
        return $row;
    }

    //修改功能
    /**
     * @return bool
     */
    public function updateArticle(){
        //修改后数据容易丢失，可以先保存起来
        $map=$this->data;
        if($this->save()===false){  //判断执行条件
            return false;
        }

        //实例化文章修改内容的模型
        $article_content_model=M('ArticleContent');
        //准备数组装内容
        $data=array(
            'article_id'=>$map['id'],
            'content'=>I('post.content'),
        );
//        dump($data);exit;
        if($article_content_model->save($data)===false){
            $this->error=$article_content_model->getError();
            return false;
        }
        return true;
    }

}