<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;

class CaptchaController extends Controller
{
    public function captcha(){
        $config=array(
            'fontsize'=>40,
            'length'=>4,
            'usezh'=>true,
//            'fonttf'=>'./2.ttf',

        );
        $verify=new Verify($config);
        $verify->entry();
        exit;
    }

}