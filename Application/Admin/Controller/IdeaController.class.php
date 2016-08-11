<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * @package Admin\Controller
 */
class IdeaController extends AdminBasicController
{
    public $idea_obj = '';
    public $mem_obj = '';
    public $idea_report = '';
    public $letter_obj = '';
    public $email_obj = '';

    public function _initialize()
    {
        $this->idea_obj = D('Idea');
        $this->mem_obj = D('Member');
        $this->idea_report = D('IdeaReport');
        $this->letter_obj = D('Letter');
        $this->email_obj = D('Email');
        parent::_initialize();
    }

    public function ideaList(){
        if(!empty($_POST['con_idea_id'])){
            $where['idea_id'] = array('LIKE',"%".$_POST['con_idea_id']."%");
        }
        if(!empty($_POST['con_idea_content'])){
            $where['content'] = array('LIKE',"%".$_POST['con_idea_content']."%");
        }
        $ideaList = $this->idea_obj->selectIdea2($where,'ctime desc',10);
        if($ideaList['list']){
            foreach($ideaList['list'] as $k=>$v){
                $ideaList['list'][$k]['image'] = array_filter(explode(',',$v['image']));
            }
            $this->assign('ideaList',$ideaList['list']);
            $this->assign('page',$ideaList['page']);
        }
        $this->display('ideaList');
    }

    //删除脑暴Idea视图
    public function deleteIdea()
    {
        if($_GET['idea_id']){
            $this->assign('idea_id',$_GET['idea_id']);
            $this->display('deleteIdea');
        }
    }
    //删除脑暴Idea操作
    public function deleteIdeaAction()
    {
        if($_POST){
            $data['m_id'] = 0;
            $data['status'] = 9;
            $data['idea_id'] = $_POST['idea_id'];
            $data['reason'] = $_POST['reason'];
            $resRe = $this->idea_report->addReport($data);
            if($resRe){
                $idea = $this->idea_obj->findIdea(array('idea_id' => $_POST['idea_id']));
                $where['idea_id'] = $_POST['idea_id'];
                unset($data);
                $data['status'] = 8;
                $resId = $this->idea_obj->editIdea($where,$data);
                if($resId){
                    unset($data);
                    $data['to_id'] = $idea['m_id'];
                    $data['le_content'] = "您的脑洞：".$idea['content'].",经核实涉嫌:".$_POST['reason']."，已被屏蔽，谢谢您的参与！";
                    //发邮件给被举报者
                    $this->email_obj->dealIdeaTOID($idea['m_id'],"您的脑洞：".$idea['content'].",经核实涉嫌:".$_POST['reason']."，已被屏蔽，谢谢您的参与！");
                    $this->letter_obj->addLetter($data);
                    $result['success'] = "删除成功！（已发送站内信和邮件通知脑洞主人）";
                }else{
                    $result['error'] = "删除失败，脑洞状态修改失败(脑洞已被删除或处理)";
                }
            }else{
                $result['error'] = "删除失败，添加记录失败";
            }
        }else{
            $result['error'] = "未知参数";
        }
        echo json_encode($result);
    }

    //批量删除
    public function deleteIdeaAll()
    {
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['idea_id'] = $v;
            $res = $this->idea_obj->deleteIdea($where);
            if($res){
                $result['success'] = "删除成功！";
            }else{
                $result['error'] = "删除失败，请稍后重试!";
            }
        }
        echo json_encode($result);
    }
}