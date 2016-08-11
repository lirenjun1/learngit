<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * Class AdminBasicController
 * @package Admin\Controller
 * 父类  添加登陆验证  权限等
 */
class AdminBasicController extends Controller {

    /**
     * 初始化
     */
    public function _initialize(){}

    /**
     * 判断登陆
     */
    public function checkLogin(){
        session('[regenerate]');   //重新生成sessionID
        $session = session('A_ID');
        if(empty($session)){
            redirect(U('Manager/newLogin'));exit;
        }
    }

    /**
     * 检查权限
     * @param string $model
     * @param string $method
     * @param bool $ajax
     */
    function checkAuth($model = '', $method = '', $ajax = false){
        //参数为空时 无权限
        $aid = session('A_ID');
        if(empty($aid)){
            $this->redirect('Admin/Manager/newLogin');
        }
        if(empty($model) || empty($method)){
            if(!$ajax){
                $this->error('没有权限1');exit;
            }else{
                $this->ajaxMsg('error','没有权限2');
            }
        }
        //对管理员的操作 只有超级管理员能够进行
        $method_arr = array('addAdmin','editAdmin','deleteAdmin','editGroup','addGroup','deleteGroup');
        if($aid != 1 && in_array($method,$method_arr)){
            if(!$ajax){
                $this->error('没有权限3');exit;
            }else{
                $this->ajaxMsg('error','没有权限4');
            }
        }
    }

    /**
     * 编辑后返回列表跳转路径设置
     */
    public function setEditBack($url){
        cookie("EDIT_BACK",$url,array('expire'=>36000));
    }

    /**
     * ajax返回数据
     * 2014-6-7
     */
    public function ajaxMsg($f,$m){
        $msg[$f] = $m;
        $this->ajaxReturn($msg,Json);
    }

    /**
     * 分页配置信息
     */
    public function getPageNumber(){
        $page_number = D('Config')->where(array('conf_id'=>1))->getField('page_number');
        return $page_number;
    }

}