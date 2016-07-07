<?php
define('BASE_URL', 'http://admin.shop.com');
return array(

    //数据库连接配置
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'127.0.0.1',
    'DB_NAME'=>'shop2',
    'DB_USER'=>'root',
    'DB_PWD'=>'2016',
    'DB_PORT'=>3306,
    'DB_PREFIX'=>'',    //表前缀
    'DB_PARAMS'=>array(), //数据库连接参数
    'DB_DEBUG'=>TRUE,   //数据库开启调试模式，记录日志
    'DB_CHARSET'=>'utf8',
    'DB_FIELDS_CACHE' => false,  // 字段缓存  开启表示可以记录sql日志

    'URL_MODEL'=>2,  //页面静态地址模式

//    'SHOW_PAGE_TRACE'=>false,  //页面调试模式
//    'SHOW_PAGE_TRACE'=>true,

    'PAGE_SIZE'=>20,  //每页显示条数
    'PAGE_THEME'=> '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',  //分页样式

    'TMPL_CACHE_ON'=> false,  //是否开启模板编译缓存，设置为false,则每次都会重新编译，刷新缓存nocache用

    'DEFAULT_MODULE'=>'Admin',  //默认入口文件模块
    'DEFAULT_CONTROLLER'=>'Index',
    'DEFAULT_ACTION'=>'index',

    'UPLOAD_SETTING'    => require __DIR__.'/upload.php',  //上传路径文件

    // session 使用数据库
//    'SESSION_TYPE'=>'Db',



);