<?php
namespace Admin\Behaviors;
use Think\Behavior;

class checkPermissionBehavior extends Behavior
{
    public function run(&$params){
        //验证用户所拥有的权限是否包括当前页面.
        //添加忽略列表 index
        $paths = array_merge([],C('URL_IGNORE'));
        //获取已登录用户的信息
//        $admin_info = session('ADMIN_INFO');
          $admin_info=login();

        //保存的session丢失  关闭浏览器
        if(!$admin_info){
            $admin_info = D('Admin')->autoLogin();    //自动登陆
        }
        //如果已经登陆才需要查询数据表
        if($admin_info){
//            if($admin_info['username'] == 'admin'){
//                return true;
//            }
            $paths = array_merge($paths,C('LOGIN_IGNORE'));
//            dump($paths);exit;
            //获取管理员id  关联到用户的访问哪些权限列表
//            $session_paths = session('PATHS');
////            dump($session_paths)；exit;
//            $paths = array_merge($paths,$session_paths);
////            dump($paths);exit;
            $paths = array_merge($paths,permission_pathes());
        }

        //判断当前的页面是否在paths中
        //获取当前请求的url地址
        $url = implode('/', [MODULE_NAME,CONTROLLER_NAME,ACTION_NAME]);
        /**
         * 1.如果没有权限 跳转到登陆页面
         * 2.提示错误,然后回退到上一个页面
         * <script type="text/javascript">location.back()</script>
         */
        if(!in_array($url, $paths)){
            header('Content-Type:text/html;charset=utf-8');
            session(null);
            $url = U('Admin/Admin/login');
            redirect($url,1,'无权访问');
        }
    }

}