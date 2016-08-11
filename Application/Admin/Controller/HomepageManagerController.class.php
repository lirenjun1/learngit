<?php

namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: guanbingqiu
 * Date: 16/5/3
 * Time: 下午2:05
 */
class HomepageManagerController extends \Admin\Controller\AdminBasicController{
    public function _initialize(){
        $this->checkLogin();
    }
    public $task = null;
    public $member = null;
    public $work = null;
    public $poster = null;
    public function __construct()
    {
        parent::__construct();
        $this->task = M('task');
        $this->work = M('work');
        $this->member = M('member');
        $this->member = M('poster');
    }

    public function focusManager(){
        //需要查询出脑暴列表
        //查询id,字段 图片 开始时间 结束时间 被打开次数 题目 钱
        //条件task 表status = 1
        $time = time();
        $where = "status = 1 AND bid_end_time > {$time}";
        $task_list = $this->task->where($where)->limit(10)->order('ctime desc')->select();
        if($task_list){
            foreach($task_list as $k =>$v){
                $task_list[$k]['m_account'] = $this->member->where(array('m_id'=>$v['m_id'],'status'=>array('neq',9)))->getField('m_account');
                $task_list[$k]['to_account'] = $this->member->where(array('m_id'=>$v['to_id'],'status'=>array('neq',9)))->getField('m_account');
            }
            $this->assign('task_list',$task_list);
        }
        $this->display();
    }
    public function changeFocus(){
        // 修改（目前作废）
        /*$where['t_id'] = $_POST['id'];
        $data['focus'] = $_POST['focus'];
        // 修改同样的排序的状态
        $nb = $this->task->where('focus='.$data['focus'])->select();
        if(!empty($nb)){
            $t_id = $nb['t_id'];
            var_dump($t_id);
            $upd = $this->task->where('t_id='.$t_id)->setField('focus',0);
            echo $this->task->getLastSql();
            $upd_res = $this->task->where($where)->setField('focus',$data['focus']);
            if($upd_res){
                $this->ajaxMsg('success','修改排序成功');
            }else{
                $this->ajaxMsg('error','修改排序失败，可能是未改变排序值');
            }
        }else{
            $upd_res = $this->task->where($where)->setField('focus',$data['focus']);
            if($upd_res){
                $this->ajaxMsg('success','修改排序成功');
            }else{
                $this->ajaxMsg('error','修改排序失败，可能是未改变排序值');
            }
        }*/

        $id = intval($_POST['id']);
        $where['t_id'] = $id;
        $data = ['focus'=>0];
        $id = $this->task->where('focus=1')->getField('t_id');
        $before = $this->task->where("t_id = {$id}")->setField('focus',0);
        $res = $this->task->where($where)->setField('focus',1);

        if($res && $before){
            die(json_encode([
                'status'=>1,
                'msg'=>'设置成功'.$id
            ]));
        }
        die(json_encode([
            'status'=>0,
            'msg'=>'失败请尝试重试'.$id
        ]));
    }
    public function changeAdcolumn(){
        if(!empty($_POST['con_workid'])){
            $where['work_id'] = array('LIKE',"%".$_POST['con_workid']."%");
        }
        $where['status'] = array('neq',9);
        $Work_list = $this->work->selectWork($where,'ctime desc',10)->select();
        if($Work_list['list']){
            foreach($Work_list['list'] as $k =>$v){
                $Work_list['list'][$k]['m_account'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_account');
                $Work_list['list'][$k]['m_email'] = $this->mem_obj->where(array('m_id'=>$v['m_id']))->getField('m_email');
            }
            $this->assign('Work_list',$Work_list['list']);
            $this->assign('page',$Work_list['page']);
        }

        $this->display();
    }

    /**
     * 编辑排序
     */
    public function editSort(){
        //修改条件 ID
        $data['focus'] = I('post.focus');
        $wher['t_id'] = I('post.id');
        $where['focus'] = I('post.focus');
        $focus = $this->task->where('focus='.$where['focus'])->select();
        if($focus != null){
            $t_id = $focus[0]['t_id'];
            $upload = $this->task->where('t_id='.$t_id)->setField('focus',0);
            if(!empty($upload)){
                $uploa = $this->task->where('t_id='.$wher['t_id'])->setField('focus',$data['focus']);
                if(!empty($uploa)){
                    $this->success('修改成功');
                }else{
                    $this->error('修改失败');
                }
            }
        }else{
            $uploa = $this->task->where('t_id='.$wher['t_id'])->setField('focus',$data['focus']);
            if(!empty($uploa)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
    }


    /**
     * 文章编辑排序
     */
    public function articlesort(){
        //修改条件 ID
        $data['focus'] = I('post.focus');
        $wher['work_id'] = I('post.id');
        $where['focus'] = I('post.focus');
        $focus = $this->work->where('focus='.$where['focus'])->select();
        $judge = $this->work->where('work_id='.$wher['work_id'])->select();

        $two = $judge[0]['status'];
        if($two == 1){
            if ($focus != null) {
                $work_id = $focus[0]['work_id'];
                $upload = $this->work->where('work_id=' . $work_id)->setField('focus', 0);
                if (!empty($upload)) {
                    $uploa = $this->work->where('work_id=' . $wher['work_id'])->setField('focus', $data['focus']);
                    if (!empty($uploa)) {
                        $this->success('修改成功');
                    } else {
                        $this->error('修改失败');
                    }
                }
            } else {
                $uploa = $this->work->where('work_id=' . $wher['work_id'])->setField('focus', $data['focus']);
                if (!empty($uploa)) {
                    $this->success('修改成功');
                } else {
                    $this->error('修改失败');
                }
            }
        }else{
            $this->error('修改失败,不能修改未通过的文章');
        }
    }

    /**
     * 文章编辑排序
     */
    public function harticlesort(){
        //修改条件 ID
        $data['h_focus'] = I('post.focus');
        $wher['work_id'] = I('post.id');
        $where['h_focus'] = I('post.focus');
        $focus = $this->work->where('h_focus='.$where['h_focus'])->select();
        $judge = $this->work->where('work_id='.$wher['work_id'])->select();

        $two = $judge[0]['status'];
        if($two == 1){
            if ($focus != null) {
                $work_id = $focus[0]['work_id'];
                $upload = $this->work->where('work_id=' . $work_id)->setField('h_focus', 0);
                if (!empty($upload)) {
                    $uploa = $this->work->where('work_id=' . $wher['work_id'])->setField('h_focus', $data['h_focus']);
                    if (!empty($uploa)) {
                        $this->success('修改成功');
                    } else {
                        $this->error('修改失败');
                    }
                }
            } else {
                $uploa = $this->work->where('work_id=' . $wher['work_id'])->setField('h_focus', $data['h_focus']);
                if (!empty($uploa)) {
                    $this->success('修改成功');
                } else {
                    $this->error('修改失败');
                }
            }
        }else{
            $this->error('修改失败,不能修改未通过的文章');
        }
    }
}