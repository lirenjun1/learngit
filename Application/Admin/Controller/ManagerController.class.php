<?php

namespace Admin\Controller;
use Think\Controller;
/**
 * Class ManagerController
 * @package Admin\Controller
 */
class ManagerController extends AdminBasicController {

    public function newLogin(){
        $session = session('A_ID');
        if(empty($session)){
            $this->display('newLogin');
        }else{
            redirect(U('Index/index'));
        }
    }

    /**
     * 管理员登陆
     */
    public function doLogin(){
        //判空
        if(empty($_POST['account'])){
            $this->ajaxMsg('error','请输入账号！！');
        }if(empty($_POST['password'])){
            $this->ajaxMsg('error','请输入密码！！');
        }if(empty($_POST['verify'])){
            $this->ajaxMsg('error','请输入验证码！！');
        }else{
            if($this->check_verify($_POST['verify'])){
                $login_res = D('Admin')->login(I('post.account'),I('post.password'));
                if($login_res){
                    $this->ajaxMsg('success','登陆成功！！');
                }else{
                    $this->ajaxMsg('error','用户名或密码错误！！');
                }
            }else{
                $this->ajaxMsg('error','验证码错误！！');
            }
        }
    }

    /**
     * 退出登录
     */
    public function logOut(){
        session('A_ACCOUNT',null);
        session('A_ID',null);
        session('A_GROUP',null);
        redirect(U('Manager/newLogin'));
    }

    /**
     * 生成验证码
     */
    public function verify(){
        $Verify = new \Think\Verify(array('length'=>4));
        $Verify->entry();
    }

    /**
     * @param $code
     * @param string $id
     * @return bool
     * 验证码检验
     */
    public function check_verify($code,$id=''){
        $verify = new \Think\Verify();
        return $verify->check($code,$id);
    }
}