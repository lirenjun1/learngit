<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * Class ServiceMemController
 * @package Admin\Controller
 * 服务商类
 */
class ReportController extends AdminBasicController
{

    public $mem_obj = '';
    public $task_obj = '';
    public $idea_obj = '';
    public $idea_report = '';
    public $letter_obj = '';
    public $email_obj = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->mem_obj = D('Member');
        $this->task_obj = D('Task');
        $this->idea_obj = D('Idea');
        $this->idea_report = D('IdeaReport');
        $this->letter_obj = D('Letter');
        $this->email_obj = D('Email');
    }

    public function del()
    {
        /*$this->checkAuth('Comment','deleteComment');
        if(empty($_REQUEST['comment_id'])){
            $this->error('您未选择任何操作对象');
        }
        $where['comment_id'] = array('IN',I('request.comment_id'));
        $data['status'] = 9;
        $upd_res = $this->comment_obj->editComment($where,$data);
        if($upd_res){
            $this->success('删除操作成功');
        }else{
            $this->error('删除操作失败');
        }*/
    }
    /*
     * 批量删除
     */
    public  function delAll(){
        /*$id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['comment_id'] = $v;
            $res = $this->comment_obj->deleteComment($where);
            if($res){
                $result['success'] = "删除成功！";
            }else{
                $result['error'] = "删除失败，请稍后重试!";
            }
        }
        echo json_encode($result);*/
    }

    /**
     * 评论列表
     */
    public function reportList()
    {
        $this->checkAuth('Report','reportList');
        $result = $this->idea_report->selectReport(null,'ctime desc',10);
        foreach($result['list'] as $k => $v){
            $mem = $this->mem_obj->findMember(array('m_id'=>$v['m_id']));
            if($mem){
                $result['list'][$k]['jubaozhe'] = $mem['m_account']?$mem['m_account']:$mem['m_email'];
            }else{
                $result['list'][$k]['jubaozhe'] = "管理员";
            }
            $idea = $this->idea_obj->findIdea(array('idea_id'=>$v['idea_id'],'status'=>array('gt',-1)));
            if($idea){
                $result['list'][$k]['i_content'] = $idea['content'];
                $result['list'][$k]['i_status'] = $idea['status'];
            }
        }
        $this->result = $result;
        $this->display('reportList');
    }

    /**
     * 详情
     */
    public function details()
    {
        $this->checkAuth('Report', 'details');
        $v = $this->idea_report->findReport(array('re_id' => $_GET['re_id']));
        $this->report = $v;

        $jubao_mem = $this->mem_obj->findMember(array('m_id' => $v['m_id']));
        if (!$jubao_mem['m_account']) $jubao_mem['m_account'] = $jubao_mem['m_email'];
        $this->jubao_mem = $jubao_mem;

        $idea = $this->idea_obj->findIdea(array('idea_id' => $v['idea_id'], 'status' => array('neq',9)));
        if (!$idea) $this->error('脑洞已经被删除了');
        elseif ($idea['status'] == 8) $this->error('脑洞已经被处理了');
        $this->idea = $idea;

        $idea_mem = $this->mem_obj->findMember(array('m_id' => $idea['m_id']));
        if (!$idea_mem['m_account']) $idea_mem['m_account'] = $idea_mem['m_email'];
        $this->idea_mem = $idea_mem;

        $task = $this->task_obj->findTask(array('t_id' => $idea['task_id']));    // <===脑暴模板数据===>

        $task['light_spot'] = empty($task['light_spot']) ? null : explode(',', $task['light_spot']);
        $task['aim_people'] = empty($task['aim_people']) ? null : explode(',', $task['aim_people']);
        $task['aim'] = empty($task['aim']) ? null : explode(',', $task['aim']);
        $task['key_word'] = empty($task['key_word']) ? null : explode(',', $task['key_word']);
        $task['other_require'] = empty($task['other_require']) ? null : explode(',', $task['other_require']);

        $player = array_filter(explode(',', $task['player']));
        /*foreach ($player as $k => $p) {
            $player[$k] = $this->mem_obj->field("m_id,m_head,m_account,m_email")->findMember(array('m_id' => $p));
        }
        $this->players = $player;*/
        $task['player_num'] = count($player);

        $this->status = $task['bid_end_time'] < time() ? "已结束" : "正在进行";
        $this->task = $task;
        $this->display('details');
    }

    public function reportAction()
    {
        $this->checkAuth('Report','reportAction');
        $k = $_GET['k'];
        if ($k == 1) {
            $idea = $this->idea_obj->findIdea(array('idea_id' => $_GET['id']));
            //删除脑洞
            $res = $this->idea_obj->deleteReportIdea(array('idea_id' => $_GET['id']));
            if($res){
                //举报成功，发送站内信
                $ideareport = $this->idea_report->findReport(array('idea_id' => $_GET['id']));
                if($ideareport){
                    //发给举报者
                    $data['to_id'] = $ideareport['m_id'];
                    $data['le_content'] = "您举报的脑洞：".$idea['content'].",审核通过，该脑暴已被屏蔽，谢谢您的参与！";
                    $this->letter_obj->addLetter($data);
                    //发邮件给举报者
                    $this->email_obj->dealIdeaMID($ideareport['m_id'],"您举报的脑洞：".$idea['content'].",审核通过，该脑暴已被屏蔽，谢谢您的参与！");
                    //发给被举报者
                    unset($data);
                    $data['to_id'] = $idea['m_id'];
                    $data['le_content'] = "您的脑洞：".$idea['content'].",经核实涉嫌:".$ideareport['reason']."，已被屏蔽，谢谢您的参与！";
                    //发邮件给被举报者
                    $this->email_obj->dealIdeaTOID($idea['m_id'],"您的脑洞：".$idea['content'].",经核实涉嫌:".$ideareport['reason']."，已被屏蔽，谢谢您的参与！");
                    $this->letter_obj->addLetter($data);
                }
                $this->success('操作成功',U('Report/reportList'));
            }else{
                $this->error('操作失败');
            }
        } elseif ($k == 2) {
            //忽略举报
            $ideareport = $this->idea_report->findReport(array('re_id' => $_GET['id']));
            $res = $this->idea_report->deleteReport(array('re_id' => $_GET['id']));
            if($res){
                if($ideareport){
                    $idea = $this->idea_obj->findIdea(array('idea_id' => $ideareport['idea_id']));
                    //发给举报者
                    $data['to_id'] = $ideareport['m_id'];
                    $data['le_content'] = "您举报的脑洞：".$idea['content']."，因证据不足，未能通过，谢谢您的参与！";
                    $this->letter_obj->addLetter($data);
                    $this->email_obj->dealIdeaMID($ideareport['m_id'],"您举报的脑洞：".$idea['content']."，因证据不足，未能通过，谢谢您的参与！");
                }
                $this->success('操作成功',U('Report/reportList'));
            }else{
                $this->error('操作失败');
            }
        } else {
            $this->error('参数错误');
        }
    }
}