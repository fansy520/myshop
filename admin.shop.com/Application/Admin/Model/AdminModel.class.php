<?php
namespace Admin\Model;
use Think\Model;
use Think\Verify;

/**
 * Class AdminModel
 * @package Admin\Model
 */
class AdminModel extends Model
{
    protected $_validate = [


        ['username','require','账号不能为空',self::MUST_VALIDATE,'',self::MODEL_INSERT],
//        ['username','','账号已存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH],    //自动触发both要分开写
        ['username','','账号已存在',self::MUST_VALIDATE,'unique',self::MODEL_UPDATE],
        ['username','','账号已存在',self::MUST_VALIDATE,'unique',self::MODEL_INSERT],
        ['password','require','密码不能为空',self::MUST_VALIDATE,'',self::MODEL_BOTH],
        ['repassword','password','两次密码不一致',self::EXISTS_VALIDATE,'confirm',self::MODEL_INSERT],
        ['email','email','邮箱格式错误',self::EXISTS_VALIDATE,'',self::MODEL_INSERT],
        ['repassword','password','两次密码不一致',self::EXISTS_VALIDATE,'confirm',self::MODEL_UPDATE],
            //修改密码时  验证旧密码和新密码是否一致
        ['oldpwd','check_password_on_change','新旧密码不能相同',self::EXISTS_VALIDATE,'callback'],

        ['captcha','check_captcha','验证码错误',self::EXISTS_VALIDATE,'callback','login'],
        ['username','require','账号不能为空',self::EXISTS_VALIDATE,'','login'],
        ['password','require','密码不能为空',self::EXISTS_VALIDATE,'','login'],


    ];

    /**
     * 自动完成的加盐  添加时间
     * @var array
     */
    protected $_auto = [
        ['salt','\Org\Util\String::randString',self::MODEL_BOTH,'function',6],
        ['add_time',NOW_TIME,self::MODEL_INSERT],
    ];


    /**
     * 自动完成验证码验证  TP中的Verify
     * @param $code
     * @return bool
     */
    protected function check_captcha($code){
        $captcha=new Verify();
        return $captcha->check($code);
    }

    /**
     * @param $old_pwd
     * @return bool
     */
    protected function check_password_on_change($old_pwd) {
        //收集旧密码
        $password = I('post.password');
        //对比密码
        if($old_pwd==$password){
            return false;
        }else{
            return true;
        }
    }


    /**获取数据
     * @return mixed
     */
    public function getList(){
        return $this->select();   //没有其他条件，数据，  直接使用select方法
    }

    /**
     * 添加管理员
     * 用管理员的id和获取角色的id   将管理员和角色关联  失败报角色错误  回滚
     * 用管理员的id 和 获取的权限id 将管理员和权限关联  失败报权限错误  回滚
     * @return bool
     */
    public function addAdmin(){
        unset($this->data[$this->getPk()]);
        //开启事务
        $this->startTrans();
        //密码加盐
        $this->data['password']=salt_string($this->data['password'], $this->data['salt']);
//        dump($this->data['password']);exit;
        if(($admin_id = $this->add())===false){   //添加管理员失败
//            dump($admin_id);exit;
            $this->rollback();
            return false;
        }

        //添加成功后需要进行角色，权限的关联
          //获取角色列表
        $role_ids = I('post.role_id');   //获取角色id
        if($role_ids){    //二维数组
            $data = [];   //预先保存，避免被修改或者丢失
            foreach($role_ids as $role_id){   //将每个角色遍历出来，才能一一关联
                $data[] = [     //中间表关联的字段 用两个表的id做媒介   ，要与表中字段一致
                    'admin_id'=>$admin_id,
                    'role_id'=>$role_id,
                ];
            }
            //准备好角色数据后，准备添加角色   直接使用M方法进行添加
            if(M('AdminRole')->addAll($data)===false){     //判断添加
//                dump($rows);exit;
                $this->error = '保存角色失败';    //错误信息
                $this->rollback();
                return false;
            }
        }

        //添加角色中，还有一个角色的额外权限的添加
          //获取权限列表
        $permission_ids=I('post.permission_id');
        if($permission_ids){   //判断权限数据获取是否成功
            $data = [];   //预先保存，避免被修改或者丢失
            foreach($permission_ids as $permission_id){   //将每个权限遍历出来，才能一一关联
                $data[] = [     //中间表关联的字段 用两个表的id做媒介  ，要与表中字段一致
                    'admin_id'=>$admin_id,
                    'permission_id'=>$permission_id,
                ];
            }


            //将管理员角色和权限的值保存到中间表AdminPermission中
            $admin_permission_model=M('AdminPermission');   //查找中间表
            if($admin_permission_model->addAll($data) === false){   //添加失败
                $this->rollback();
                $this->error = '保存权限失败';
                return false;
            }

        }

        $this->commit();    //提交
        return true;
//        $this->display();
    }

    /**
     * 管理员修改  同时角色和权限也要修改
     * 当密码修改后  盐和密码也将被修改
     * @return bool
     */
    public function updateAdmin(){
        //开启事务
        $this->startTrans();
        $request_data = $this->data;          //避免冲突将this换一个方式
        if(empty($this->data['password'])){   //解决空操作的情况下  检查密码是否被修改
            unset($this->data['password']);
            unset($this->data['salt']);
        }else{
            $this->data['password'] = salt_string($this->data['password'], $this->data['salt']);
        }
        //执行结果 如果保存失败就回滚
        if(count($this->data) > 1){
            if($this->save()===false){   //保存失败
                $this->rollback();
                return false;
            }
        }

        //修改数据后同时也要将管理员和角色进行管理  用id作为媒介  关联到中间表AdminRole中
        //获取管理员，角色  进行关联
        $role_ids = I('post.role_id');   //获取中间表角色id
//        dump($role_ids);exit;
        //更先前 删除原有的角色
        $admin_role_model = M('AdminRole');   //找到中间表
        $admin_role_model->where(['admin_id'=>$request_data['id']])->delete();   //根据条件删除中间表的数据
        if($role_ids){
            $data = [];
            //保存角色相关字段  用数组保存  并且遍历出来，要与表中字段一致
            foreach($role_ids as $role_id){
                $data[] = array(
                    'admin_id'=>$request_data['id'],
                    'role_id'=>$role_id,
                );
            }
//            dump($data);exit;
            //角色关联添加
            if($admin_role_model->addAll($data) === false){
                $this->error = '保存角色失败';
                $this->rollback();
                return false;
            }
        }


        //角色关联完后    还需要关联额外权限
        //获取权限
        $permission_ids = I('post.permission_id');
        //更新数据前  删除原有的关联权限
        $admin_permission_model = M('AdminPermission');  //找中间表
        $admin_permission_model->where(['admin_id'=>$request_data['id']])->delete();   //根据条件删除
        if($permission_ids){
            //保存数据
            $data = [];
            foreach($permission_ids as $permission_id){    //遍历数组
                $data[] = array(                                //设置先关字段值，要与表中字段一致
                    'admin_id'=>$request_data['id'],
                    'permission_id'=>$permission_id,
                );
            }
//            dump($data);exit;
            //执行权限添加   注意将$data传值
            if($admin_permission_model->addAll($data)===false){
//                dump($this->getLastSql());exit;
                $this->error = '保存权限失败';
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;


    }

    /**获取管理员信息 同时的角色和权限
     * @param $id
     * @return mixed
     */
    public function getAdminInfo($id){
        //查询一条数据
        $row = $this->find($id);
        //查询管理员相-角色关联的信息
        $row['role_ids'] = json_encode(M('AdminRole')->where(['admin_id'=>$id])->getField('role_id',true));
        //查询管理员-权限关联的信息
        $row['permission_ids'] = json_encode(M('AdminPermission')->where(['admin_id'=>$id])->getField('permission_id',true));
        return $row;    //返回查询的结果
    }

    /**
     * 删除管理员  同时删除关联的角色和权限信息
     * @param array|mixed $id
     * @return bool
     */
    public function delete($id){
        //开启事务
        $this->startTrans();
        //删除基本信息
        if($this->delete($id) === false){
            $this->rollback();
            return false;
        }
        //删除角色关联关系
        if(M('AdminRole')->where(['admin_id'=>$id])->delete() === false){
            $this->error = '删除角色失败';
            $this->rollback();
            return false;
        }

        //删除权限关联关系
        if(M('AdminPermission')->where(['admin_id'=>$id])->delete() === false){
            $this->error = '删除角色失败';
            $this->rollback();
            return false;
        }
        $this->commit();   //执行提交
        return true ;
    }

    /**
     * 验证username password  密码是加盐数据
     * 保存ip 和 时间
     * @return bool
     */
    public function login(){
        $request_data = $this->data;
        //查询并保存用户信息
        $userinfo = $this->getByUsername($this->data['username']);
//        var_dump($userinfo);exit;
        if(empty($userinfo)){    //查询值为空
            $this->error = '用户不存在';   //提示错误信息
            return false;
        }
        //将密码加盐后和数据库中的密码对比
        //将密码加盐
        $password = salt_string($request_data['password'], $userinfo['salt']);
        //对比密码
        if($password == $userinfo['password']){   //密码正确
            //修改记录保存最后登陆的时间和ip
            $data = array(
                'id'=>$userinfo['id'],
                'last_login_time'=>NOW_TIME,
                'last_login_ip'=>  get_client_ip(1),   //值为 1 才是正确的ip
            );
            $this->setField($data);
//            dump($data['last_login_ip']);exit;
//            session('ADMIN_INFO',$userinfo);   //将用户的信息，包括权限和角色等保存到session中
            login($userinfo);
            //获取用户的可以访问的path列表
            $this->save_permission($userinfo['id']);
            //判断是否需要自动登陆  从令牌获取
            $this->save_token($userinfo);
            return true;
        }else{
            //查询到的密码不真确
            $this->error = '密码不正确';
            return false;
        }
    }

    /**
     * 保存权限到中间表
     * @param $admin_id
     */
    private function save_permission($admin_id){
        //获取管理员所能够看到的请求url  拼凑sql语句
        //  admin、admin_role、role_permission、admin_permission、permission  5个表的数据
//        $sql = 'SELECT DISTINCT path FROM
//                (SELECT  permission_id  FROM admin_role AS ar
//                LEFT JOIN role_permission AS rp  ON ar.`role_id` = rp.`role_id`
//                WHERE ar.`admin_id` = '.$admin_id.'
//                UNION
//                SELECT permission_id  FROM admin_permission AS ap
//                WHERE ap.`admin_id` = '.$admin_id.')
//                AS t  LEFT JOIN permission AS p  ON t.permission_id = p.`id`
//                WHERE p.`path` <> "" ';

        if($admin_id==1){
            $sql='select id,path from permission';
        }else{
            $sql='SELECT DISTINCT p.id,path FROM
                  (SELECT  permission_id  FROM admin_role AS ar
                  LEFT JOIN role_permission AS rp  ON ar.`role_id` = rp.`role_id`
                  WHERE ar.`admin_id` = ' . $admin_id . '
                  UNION
                  SELECT permission_id  FROM admin_permission AS ap
                  WHERE ap.`admin_id` = ' . $admin_id . ') AS t
                  LEFT JOIN permission AS p  ON t.permission_id = p.`id`
                  WHERE p.`path` <> "" ';
        }
        $permissions_info = M()->query($sql);   //执行sql语句
        $paths = [];     //将path地址保存到数组
        $pids  = [];     //将id值地址保存到数组
        if($permissions_info){   //执行成功
            foreach($permissions_info as $permission_info){
                $paths[] = $permission_info['path'];   //保存数组  数组中不能传值，否则路径错误，找不到值
                $pids[]  = $permission_info['id'];
//                dump($paths['path'],$paths[]);exit;
            }
        }
        //将能访问的路径保存到session中
//        session('PATHS',$paths);
        permission_pathes($paths);
        permission_ids($pids);
    }


    /**
     * 保存令牌信息  存到cookie中
     * 对比完后存到数据表欧中
     * @param array $admininfo
     * @param bool|false $is_auto_login
     */
    private function save_token(array $admininfo,$is_auto_login=false) {
        //判断是否需要自动登陆   从页面获取
        if(I('post.remember') || $is_auto_login){
            //生成token令牌数据   就是盐
            $data = array(
                'id'  => $admininfo['id'],    //对应的id
                'login_token' => md5(mcrypt_create_iv(32)),   //mcrypt_create_iv  生成盐的函数
            );
//            dump($data);exit;
            //页面数据  保存到cookie中
            cookie('admin_token', $data, 604800);        //记录一周
            //保存到数据库中
            $this->setField($data);
        }
    }

    /**自动登登陆
     * @return bool|mixed
     */
    public function autoLogin(){
        //  用户登陆后  自动登陆   从cookie中获取数据
        $cookie_token = cookie('admin_token');
        // 查看是否有令牌  如果没有令牌,无需查询数据表，
        if(!$cookie_token){
            return false;
        }

        //验证cookie中的令牌和数据表中的是否一致
        $admin_info  = $this->where($cookie_token)->find();
//        dump($admin_info);exit;
        //查询到令牌后  保存session
        if($admin_info){
            //为了安全,重新生成令牌
            $this->save_token($admin_info, true);
            //存储  session
            login($admin_info);
//            dump($admin_info);exit;
            //  登录后，从数据库中查找  获取用户相关权限
            $this->save_permission($admin_info['id']);
        }
        //保存信息，自动登录
        return $admin_info;
    }

    /**
     * 改变密码
     * @return bool
     */
    public function changePwd() {
        //修改密码前进行对比 判断旧密码是否合法
        $admin_info = login();   //登录信息
        $password = salt_string(I('post.oldpwd'), $admin_info['salt']);   //将密码加盐加密
        if($password == $admin_info['password']){     //判断新旧密码是否合法，一致
            $data = array(
                'id'=>$admin_info['id'],
                'salt'=>$this->data['salt'],
                'password'=>  salt_string($this->data['password'], $this->data['salt']),
            );
//            dump($data);exit;
            //修改
            $admin_info = array_merge($admin_info,$data);  //将新密码和盐组合
            //用户新的信息  保存到session中
            login($admin_info);
            return $this->setField($data);
        }else{
            $this->error = '原密码不正确';
            return false;
        }

    }





}