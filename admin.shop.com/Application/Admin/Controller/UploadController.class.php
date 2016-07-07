<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Upload;

class UploadController extends Controller
{
    //文件上传
    public function upload()
    {
        $options = C('UPLOAD_SETTING');  //获取配置参数
        $upload = new Upload($options);   //实例化上传类对象
        $file_infos = $upload->upload(); //上传   uploadone需要知道控件名字，所以使用upload
        if ($file_infos){
            $file_info = array_shift($file_infos);
            $root_path = str_replace(APP_PATH,'',$options['rootPath']);

            if($upload->driver == 'Qiniu'){
                $file_url = $file_info['url'];
            }else{
                $base_url = substr(APP_PATH,1);
//                var_dump($base_url);exit;
                $file_url = $base_url.$root_path.$file_info['savepath'].$file_info['savename'];
            }

            $return = [
                'status' => 1,
                'msg' => '',
                'file_url' => $file_url,
            ];
//            var_dump($return);exit;
        }else{
            $return = [
                'status' => 0,
                'msg' => $upload->getError(),
                'file_url' => '',
            ];
        }
//        var_dump($return);exit;
        //5.返回上传结果(json)
        $this->ajaxReturn($return);
    }


}


