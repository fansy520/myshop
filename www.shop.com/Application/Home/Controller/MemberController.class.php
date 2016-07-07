<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller{

    /**
     * @var \Home\Model\MemberModel
     */
    private $_model = null;

    /**
     * 构造函数
     */
    protected function _initialize(){
        $this->_model = D('Member');

        $meta_titles = array(
            'register' => '用户注册',
            'login'    => '用户登录',
            'logout'   => '退出',
        );
        $meta_title  = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '用户登录';
        $this->assign('meta_title', $meta_title);
    }


    /**
     * 登录  采用验证规则 create中传入场景login
     */
    public function login(){
        if(IS_POST){
            if($this->_model->create('', 'login') === false){    //收集数据 ，传入场景login
                $this->error($this->_model->getError());
            }
            if($this->_model->login() === false){                //登录验证   会员信息
                $this->error($this->_model->getError());
            }
            //验证成功 跳转
//            $this->success('登录成功', U('Index/index',['nocache'=>NOW_TIME]));
            //判断是否从购物车过来的，登录成功后跳转回购物车
             $url=cookie('__self__')?:U('Index/index',['nocache'=>NOW_TIME]);
            //当购物车使用完毕，销毁购物车
            cookie('__self__',null);
            $this->success('登录成功',$url);

        }else{
            $this->display();
        }
    }


    /**
     * 注册功能
     */
    public function register(){
        if(IS_POST){
            if($this->_model->create('', 'reg') === false){   //收集数据   传入场景
                $this->error($this->_model->getError());
            }
            if($this->_model->addMember() === false){         //添加数据   会员信息
                $this->error($this->_model->getError());
            }
            $this->success('注册成功,请前往登录', U('login',['nocache'=>NOW_TIME])); //添加成功 跳转
        } else {
            //渲染视图
            $this->display('');
        }
    }



    /**
     * 验证
     * @param $telphone
     */
    public function sendSms($telphone){
        $code = (string)mt_rand(1000, 9999);   //转义为字符串验证 四位数
        //将验证码保存到session中,以便验证
        session('REG_CODE',$code);
//        dump($code);exit;
        $data = array(
            'code'=>$code,
            'product'=>'霸道总裁',
        );
//        dump($data);exit;
        //手机号发送验证内容
        $status = sendSms($telphone, $data);
//        dump($status);exit;
        $this->ajaxReturn($status);
    }

    /**
     * 获取数据后  采用ajax方法处理
     */
    public function checkExist(){
        //搜集数据
        $con = I('get.');
        //匹配都就说明不能用   反之则可以
        if($this->_model->where($con)->count()){
            $this->ajaxReturn(false);
        }else{
            $this->ajaxReturn(true);
        }
    }

    /**
     * 返回ajax请求数据
     */
    public function getUserInfo(){
        //登录信息
        $userinfo=login();
        if($userinfo){      //取出数据，有则显示，无则显示空
            $username= $userinfo['username'];
        }else{
            $username = '';
        }
        //返回ajax请求数据
        $this->ajaxReturn($username);

    }

    /**
     * 退出登录   记得添加忽略权限
     */
    public function logout(){
        //清空session和cookie的数据，跳转到首页
        session(null);
        cookie(null);
        $this->success('退出成功',U('Index/index'));
    }







}
