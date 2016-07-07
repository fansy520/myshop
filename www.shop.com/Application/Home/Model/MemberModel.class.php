<?php
namespace Home\Model;

use Think\Model;
use Think\Verify;

/**
 * Class MemberModel
 * @package Home\Model
 */
class MemberModel extends Model
{
    /**
     * 注册时
     * username    必填 唯一  3-20位
     * password    必填 6-20位
     * repassword  和password必须一致
     * email       必填 email格式
     * tel         必填 满足中国手机号码规则
     * todo::      tel_code 必填 自定义验证规则(和发送的短信内容匹配)
     * img_code    必填 使用自定义验证规则
     * agree       必填
     * @var array
     */
    protected $_validate = array(
        //注册验证
        ['username','require','用户名不能为空',self::MUST_VALIDATE,'','reg'],
        ['username','','用户名已存在',self::MUST_VALIDATE,'unique','reg'],
        ['username','3,20','用户名长度不合法',self::MUST_VALIDATE,'length','reg'],
        ['password','require','密码不能为空',self::MUST_VALIDATE,'','reg'],
        ['password','6,20','密码长度不合法',self::MUST_VALIDATE,'length','reg'],
        ['repassword','password','两次密码不一致',self::MUST_VALIDATE,'confirm','reg'],
        ['email','email','邮箱不合法',self::MUST_VALIDATE,'','reg'],
        ['tel','/^1(3|4|5|7|8)\d{9}$/','手机号码不合法',self::MUST_VALIDATE,'regex','reg'],
        ['img_code','check_img_code','验证码不正确',self::MUST_VALIDATE,'callback','reg'],
        ['tel_code','check_tel_code','验证码不正确',self::MUST_VALIDATE,'callback','reg'],
        ['agree','require','必须同意许可协议',self::MUST_VALIDATE,'','reg'],

        //登录验证
        ['username','require','用户名不能为空',self::MUST_VALIDATE,'','login'],
        ['password','require','密码不能为空',self::MUST_VALIDATE,'','login'],
        ['img_code','check_img_code','验证码不正确',self::MUST_VALIDATE,'callback','login'],

    );

    /**
     * 自动完成  加盐  注册时间  初始的状态各种验证规则
     * @var array
     */
    protected $_auto = array(
        ['add_time',NOW_TIME,'reg'],
        ['salt','\Org\Util\String::randString','reg','function',6],
        ['status',0,'reg'],
    );

    /**
     * 验证码图片引入
     * @param $code
     * @return bool
     */
    protected function check_img_code($code){
        $verify=new Verify();
        return $verify->check($code);
    }

    /**
     * 匹配用户填写的验证码
     * @param $code
     * @return bool
     */
    protected function check_tel_code($code){
        $session_code = session('REG_CODE');
        session('REG_CODE',null);           //当次用完,销毁掉
        if($session_code==$code){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 用户注册    邮件激活待完善
     * @return mixed
     */
    public function addMember(){
        //对密码进行加密
        $this->data['password']=salt_string($this->data['password'], $this->data['salt']);
        $code = session('REG_CODE');    //查看手机验证码是否匹配
        return $this->add();            //返回这个结果
    }

    /**
     * 用户登录
     * @return bool
     */
    public function login(){
        $request_data = $this->data;     //获取用户信息
        $user_info = $this->getByUsername($request_data['username']);   //将用户名保存
//        var_dump($user_info);exit;
        if(!$user_info){    //获取信息失败
            $this->error = '用户不存在';     //错误信息
            return false;
        }
        $password =  salt_string($request_data['password'], $user_info['salt']);
//        var_dump($request_data);exit;
//        dump($password);exit;
        if($password != $user_info['password']){    //密码匹配错误
            $this->error = '密码错误';
            return false;
        }
        //保存   session信息
        login($user_info);
        //登录成功  保存cookie购物车信息到数据库中
        D('Cart')->cookie2Db();
        return true;
    }









}