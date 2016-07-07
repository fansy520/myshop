<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class GalleryController
 * @package Admin\Controller
 */
class GalleryController extends Controller
{
    public function delete($id){
        //实例化相册对象
        $galleryModel=M('GoodsGallery');
        //执行删除
        if($galleryModel->delete($id)===false){
            $this->error($galleryModel->getError());
        }else{
            $this->success('删除成功');
        }
    }

}