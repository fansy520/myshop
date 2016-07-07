<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller
{
    /**
     * @var \Admin\Model\AdminModel
     */
    private $_model=null;

    /**
     * 初始化执行的代码
     */
    protected function _initialize(){
        $this->_model = D('Admin');
        $meta_titles  = array(
            'index' => '管理管理员',
            'add'   => '添加管理员',
            'edit'  => '修改管理员',
        );
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '管理管理员';
        $this->assign('meta_title', $meta_title);
    }

    /**
     * 管理员展示页面
     */
    public function index(){
        $this->assign('rows', $this->_model->getList());
        $this->display();
    }

    /**
     * 添加管理员
     */
    public function add(){
        if(IS_POST){
            if($this->_model->create() === false){   //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->addAdmin() === false){   //添加管理员  注意权限，角色的关联  还有一个额外权限
                $this->error($this->_model->getError());
            }
            $this->success('添加成功', U('index',['nocache'=>NOW_TIME]));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**修改管理员
     * @param $id
     */
    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){  //获取数据
                $this->error($this->_model->getError());
            }
            if($this->_model->updateAdmin() === false){  //修改保存权限，关系到层级，所以需要其他model代码处理
                $this->error($this->_model->getError());
            }
            $this->success('修改成功', U('index',['nocache'=>NOW_TIME]));
        }else{
            //获取一条记录
            $this->assign('row', $this->_model->getAdminInfo($id));
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除管理员   注意权限
     * @param $id
     */
    public function delete($id){
        //删除管理员
        if($this->_model->deleteAdmin($id) === false){
            $this->error($this->_model->getError());
        }
        $this->success('删除成功', U('index'));
    }

    /**
     * 分布数据的公共方法
     */
    private function _before_view() {
        //获取所有的角色  管理员有对应的角色
        $this->assign('roles',D('Role')->getList());

        //获取所有的所有权限  管理员角色有对应的权限
        $this->assign('permissions', json_encode(D('Permission')->getList()));
    }



    /**
     * 登录功能
     */
    public function login(){
        if(IS_POST){
            //验证用户登录
            if($this->_model->create('','login')===false){   //接受数据，初始化为空值 提示登录   接受数据时指定login
                $this->error($this->_model->getError());
            }
            if($this->_model->login() === false){
                $this->error($this->_model->getError());
            }
            $this->success('登陆成功',U('Index/index',['nocache'=>NOW_TIME]));
        }else{
            $this->display();
        }
    }

    /**
     * 退出用户  同时清除session 和cookie的值
     */
    public function logout(){
        //用户登陆后 username保存在session中，将值设为空,退出即为null
        $admin_info = login();
//        dump($admin_info);exit;
        $data = array(
            'id'=>$admin_info['id'],
            'login_token'=>'',    //将令牌设置为空字串
        );
//        dump($data);exit;
        if($this->_model->setField($data)===false){
            $this->error($this->_model->getError());

        }else{
            //退出成功跳转
            session(null);
            cookie(null);
            $this->success('退出成功',U('login',['nocache'=>NOW_TIME]));
        }
//        session(null);


    }




    /**
     * 修改密码
     */
    public function changePwd(){
        if(IS_POST){
            if($this->_model->create()===false){           //收集数据
                $this->error($this->_model->getError());
            }
            if($this->_model->changePwd()===false){        //修改数据
                $this->error($this->_model->getError());
            }
            $this->success('密码修改成功',U('Index/main',['nocache'=>NOW_TIME]));
        }else{
            //原始密码，新密码对比
            $this->display();
        }
    }





}