<?php
namespace Admin\Controller;
use Think\Controller;

class ContactController extends AdminBasicController
{

    public $contact_obj = '';

    public function _initialize()
    {
        $this->contact_obj = D('Contact');
    }

    /**
     *   删除消息
     */
    public function deleteContact()
    {
        if (empty($_GET['contact_id'])) {
            $this->error('您未选择任何操作对象');
        }
        $where['contact_id'] = $_GET['contact_id'];
        $data['status'] = 9;
        $data['utime'] = time();
        $res = $this->contact_obj->where($where)->save($data);
        if ($res) {
            //其他删除操作
            $this->success('删除操作成功');
        } else {
            $this->error('删除操作失败');
        }
    }

    //批量删除信息
    public function deleteContactAll(){
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['contact_id'] = $v;
            $res = $this->contact_obj->deleteContact($where);
            if($res){
                $result['success'] = "删除成功！";
            }else{
                $result['error'] = "删除失败，请稍后重试!";
            }
        }
        echo json_encode($result);
    }

    /**
     *  消息列表
     */
    public function messageList()
    {
        $where['status'] = array('neq', 9);
        $contact_list = $this->contact_obj->selectContact($where, 'ctime desc', '15');
        $data = $contact_list['list'];
        $this->assign('contactList', $data);
        $this->assign('page', $contact_list['page']);
        $this->display('contactList');
    }
}