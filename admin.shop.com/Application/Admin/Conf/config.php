<?php
return array(
    'TMPL_PARSE_STRING'=>array(
        '__IMG__' => SRC_URL.'Public/'.MODULE_NAME.'/Images',
        '__CSS__' => SRC_URL.'Public/'.MODULE_NAME.'/Css',
        '__JS__' => SRC_URL.'Public/'.MODULE_NAME.'/Js',
        '__UPLOADIFY__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/uploadify',
        '__LAYER__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/layer',
        '__ZTREE__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/ztree',
        '__TREEGRID__'=>SRC_URL.'Public/'.MODULE_NAME.'/ext/treegrid',
        '__UE__'=>SRC_URL.'Public/'.MODULE_NAME.'/ext/ueditor',

        '__ROOT__'=>SRC_URL,
    ),

    //不使用RBAC限制的地址
    'URL_IGNORE' => [
    'Admin/Admin/login',
    'Admin/Captcha/captcha',
    'Admin/Admin/logout',
    'Admin/Editor/ueditor',
    'Admin/Upload/upload',



//    'Admin/Menu/index',
//    'Admin/Admin/add',
//    'Admin/Role/add',


],
    //登陆用户都可见页面地址
    'LOGIN_IGNORE'=>[
    'Admin/Index/index',
    'Admin/Index/top',
    'Admin/Index/menu',
    'Admin/Index/main',
    'Admin/Admin/logout',
    'Admin/Admin/changePwd',
],

//    'COOKIE_PREFIX'=>'admin_shop_com_',



);