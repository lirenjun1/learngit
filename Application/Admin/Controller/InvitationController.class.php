<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class ServiceMemController
 * @package Admin\Controller
 * 服务商类
 */
class InvitationController extends AdminBasicController{
    public $invitation_obj    = '';
    public $email_obj = '';
    public function _initialize(){
        parent::_initialize();
        $this->invitation_obj    = D('Invitation');
        $this->email_obj = D('Email');
    }

    /**
     */
    public function generateInvitation(){
        $this->checkAuth('Invitation','addInvitation');
        $url = $this->email_obj->generateInvitationUrl();
        //发送信息
        if ($url) {
            $arr = array('flag' => 0, 'message' => $url);
        } else {
            $arr = array('flag' => 1, 'message' => '');
        }
        print  json_encode($arr);
    }

    public function sendMail() {
        $result = $this->email_obj->sendInvitation($_POST['email'], $_POST['url']);
        print json_encode($result);
    }

    public function addInvitation() {
        $this->display('addInvitation');
    }

    /**
     * 服务商列表
     */
    public function invitationList(){
        $invitation_list = $this->invitation_obj->selectInvitation(array(),'time desc',$this->getPageNumber());
        if($invitation_list['list']){
            foreach($invitation_list['list'] as $k =>$v){
                if($v['status'] == 9){
                    $invitation_list['list'][$k]['status'] = "已使用";
                }else {
                    $invitation_list['list'][$k]['status'] = "未使用";
                }

            }
            $this->assign('invitation_list',$invitation_list['list']);
            $this->assign('page',$invitation_list['page']);
            $this->assign('count', $this->invitation_obj->usedCount());
        }
        $this->display('invitationList');
    }
}