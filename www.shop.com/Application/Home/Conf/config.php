<?php
return array(
    'TMPL_PARSE_STRING'=>array(
        '__IMG__' => SRC_URL.'Public/'.MODULE_NAME.'/images',
        '__CSS__' => SRC_URL.'Public/'.MODULE_NAME.'/css',
        '__JS__' => SRC_URL.'Public/'.MODULE_NAME.'/js',
        '__JQUERY_VALIDATION__'=>SRC_URL.'Public/'.MODULE_NAME.'/ext/jquery_validation',

//
//        '__UPLOADIFY__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/uploadify',
//        '__LAYER__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/layer',
//        '__ZTREE__' => SRC_URL.'Public/'.MODULE_NAME.'/ext/ztree',
//        '__TREEGRID__'=>SRC_URL.'Public/'.MODULE_NAME.'/ext/treegrid',
//        '__UE__'=>SRC_URL.'Public/'.MODULE_NAME.'/ext/ueditor',
    ),


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

    'PAGE_SIZE'=>10,  //每页显示条数
    'PAGE_THEME'=> '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',  //分页样式

    'TMPL_CACHE_ON'=> false,  //是否开启模板编译缓存，设置为false,则每次都会重新编译，刷新缓存nocache用

    'DEFAULT_MODULE'=>'Home',  //默认入口文件模块
    'DEFAULT_CONTROLLER'=>'Index',
    'DEFAULT_ACTION'=>'index',

//    'UPLOAD_SETTING'    => require __DIR__.'/upload.php',  //上传路径文件

    // session 使用数据库
//    'SESSION_TYPE'=>'Db',



//session存入redis
    //数据缓存配置
    'SESSION_AUTO_START' => true,      // 是否自动开启Session
    'SESSION_TYPE'       => 'Redis',   //session类型
    'SESSION_PERSISTENT' => 1,           //是否长连接(对于php来说0和1都一样)
    'SESSION_CACHE_TIME' => 1,           //连接超时时间(秒)
    'SESSION_EXPIRE'     => 0,           //session有效期(单位:秒) 0表示永久缓存
    'SESSION_PREFIX'     => 'sess_',     //session前缀
    'SESSION_REDIS_HOST' => '127.0.0.1', //分布式Redis,默认第一个为主服务器
    'SESSION_REDIS_PORT' => '6379',      //端口,如果相同只填一个,用英文逗号分隔
    'SESSION_REDIS_AUTH' => '',          //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
    'DATA_CACHE_TIMEOUT' => 3600,
    'COOKIE_PREFIX'=>'www_shop_com_',


//页面静态化的配置
    'HTML_CACHE_ON'      => true, // 开启静态缓存
    'HTML_CACHE_TIME'    => 3600, // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'   => '.html', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES'   => array(// 定义静态缓存规则     // 定义格式1 数组方式
//        'Index:index' => array('{$_SERVER.REQUEST_URI|md5}'),
        'Index:' => array('{:action}_{id}'),
//        'goods' => array('{:action}_{id}'),
    ),

    'EMAIL_SETTING'=>  require __DIR__.'/email.php',






);