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
//单文件上传
    public function upload1($img,$dir){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Application/Public/'; // 设置附件上传根目录
        $upload->savePath =      'Uploads/'.$dir.'/'; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($img);
        if(!$info) {// 上传错误提示错误信息
            return 'false';
        }else{// 上传成功 获取上传文件信息
            return $info['savepath'].$info['savename'];
        }
    }
    //多图片上传
    public function upload($dir){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =    './Application/Public/Uploads/'.$dir.'/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $img[]=$info['savepath'].$info['savename'];
//            foreach($info as $file){
//                $img[]=$file['savepath'].$file['savename'];
//            }
            return $img;
        }
    }
}