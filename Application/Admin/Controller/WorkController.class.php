<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Class WorkController
 * @package Admin\Controller
 * 优秀作品
 */
class WorkController extends AdminBasicController{
    public $work_obj = '';
    public $mem_obj = '';
    public function _initialize(){
        parent::_initialize();
        $this->work_obj = D('Work');
        $this->mem_obj = D('Member');
    }
    /**
     * 审核作品
     */
    public function verifyWork(){
        $this->checkAuth('Work','verifyWork');
        $where['work_id'] = $_GET['work_id'];
        //审核通过为 1 不通过为 2
        $data['status'] = $_GET['status'];
        $result = $this->work_obj->saveWork($where,$data);

        if($result){
            $this->success('操作成功',U('Work/workList'));
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 作品列表
     */
    public function workList(){
        if(!empty($_POST['con_workid'])){
            $where['work_id'] = array('LIKE',"%".$_POST['con_workid']."%");
        }
        $where['status'] = array('neq',9);
        $where['status_but'] = 0;
        $Work_list = $this->work_obj->selectWork($where,'ctime desc',10);
        if($Work_list['list']){
            foreach($Work_list['list'] as $k =>$v){
                $Work_list['list'][$k]['m_account'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_account');
                $Work_list['list'][$k]['m_email'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_email');
            }
            $this->assign('Work_list',$Work_list['list']);
            $this->assign('page',$Work_list['page']);
        }
        $this->display('workList');
    }

    public function workAdmin(){
        if(!empty($_POST['con_workid'])){
            $where['work_id'] = array('LIKE',"%".$_POST['con_workid']."%");
        }
        $where['status'] = array('neq',9);
        $where['status_but'] = 1;
        $Work_list = $this->work_obj->selectWork($where,'ctime desc',10);
        if($Work_list['list']){
            foreach($Work_list['list'] as $k =>$v){
                $Work_list['list'][$k]['m_account'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_account');
                $Work_list['list'][$k]['m_email'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_email');
            }
            $this->assign('Work_list',$Work_list['list']);
            $this->assign('page',$Work_list['page']);
        }
        $this->display('workAdmin');
    }
    /**
     * 作品详情
     */
    public function workDetail(){
        $where['work_id'] = $_GET['work_id'];
        $work = $this->work_obj->findWork($where);
        $this->assign('work',$work);
        $this->display('workDetail');
    }
    /**
     * 删除作品
     */
    public function deleteWork(){
        $this->checkAuth('Work','deleteWork');
        $where['work_id'] = array('IN',I('request.work_id'));
        $data['status'] = 9;
        $upd_res = $this->work_obj->saveWork($where,$data);
        if($upd_res){
            $this->success('删除作品成功');
        }else{
            $this->error('删除作品失败');
        }
    }
    /*
     * 批量删除作品
     */
    public function deleteWorkAll(){
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['work_id'] = $v;
            $res = $this->work_obj->deleteWork($where);
            if($res){
                $result['success'] = "删除成功！";
            }else{
                $result['error'] = "删除失败，请稍后重试!";
            }
        }
        echo json_encode($result);
    }
    //待审核作品视图
    public function checkWorkList(){
        $where['status'] = array('eq',0);
        $Work_list = $this->work_obj->selectWork($where,'ctime desc',10);
        if($Work_list['list']){
            foreach($Work_list['list'] as $k =>$v){
                $Work_list['list'][$k]['m_account'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_account');
            }
            $this->assign('Work_list',$Work_list['list']);
            $this->assign('page',$Work_list['page']);
        }
        $this->display('workList');
    }
    // 加载作品发布页面
    public function addWork(){
        $this->display('addWork');
    }
}