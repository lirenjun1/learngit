<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-2-9
 * Time: 下午2:27
 */
namespace Admin\Controller;
use Think\Controller;

/**
 * Class LetterController
 * @package Admin\Controller
 *  站内信类
 */
class LetterController extends AdminBasicController
{

    public $member = '';
    public $letter = '';
    public $email_obj = '';
    public $mem_obj = '';
    public $sms_obj = '';


    public function _initialize()
    {
        $this->member = D('Member');
        $this->letter = D('Letter');
        $this->email_obj = D('Email');
        $this->mem_obj = D('Member');
        $this->sms_obj = D('Sms');
    }

    //管理员发送短信
    public function sendSms(){
        if($_POST){
            $phone = $_POST['phone'];
            $content = $_POST['content'];
            if(preg_match("/^1[34578]\d{9}$/", $phone)){
                if(empty($content)){
                    $this->error("发送内容不能为空！");
                }else{
                    $this->sms_obj->sendSms($phone, $content);
                    $this->success("发送成功！");
                }
            }else{
                $this->error("非法的手机号");
            }

        }else{
            $this->display('sendSms');
        }
    }

    /**
     *  发送站内信
     */
    public function addLetter()
    {
        //$this->checkAuth('Letter','addLetter');
        if (empty($_POST)) {
            $this->display('addLetter');
        } else {
            $data = $this->letter->create();
            if ($data) {
                //textarea与HTML内容转换
                //替换掉 换行
                $data['le_content'] = str_replace("\n", "<br>", $_POST['le_content']);
                //替换掉 空格
                $data['le_content'] = str_replace(" ", "&nbsp;", $data['le_content']);
                $data['status'] = '0';
                $data['to_id'] = '0';
                $data['is_read'] = '0';
                $add_res = $this->letter->addLetter($data);

                if ($add_res) {
                    $this->success('发送成功', U('Letter/messageList'));
                } else {
                    $this->error('发送失败');
                }
            } else {
                $this->error($this->letter->getError());
            }
        }
    }


    /**
     *   删除站内信
     */
    public function deleteLetter()
    {
        $this->checkAuth('Letter', 'deleteLetter');
        if (empty($_REQUEST['le_id'])) {
            $this->error('您未选择任何操作对象');
        }
        $where['le_id'] = array('IN', I('request.le_id'));
        $data['status'] = 9;
        $data['utime'] = time();
        $res = $this->letter->where($where)->save($data);
        if ($res) {
            //其他删除操作
            $this->success('删除操作成功');
        } else {
            $this->error('删除操作失败');
        }
    }

    /**
     *  站内信详情
     */
    public function letterInfo()
    {
        $where['le_id'] = $_GET['le_id'];
        $letter = $this->letter->findLetter($where);
        //textarea与HTML 内容转换
        if (strpos($letter['le_content'], '<br>')) {
            //删除既有的br
            $letter['le_content'] = str_replace("<br>", "", $letter['le_content']);
        }
        $this->assign('letter', $letter);
        $this->display('letterInfo');
    }

    /**
     *  站内信列表
     */
    public function messageList()
    {
        if ($_POST['content']) {
            //关键字查询
            $where['le_content'] = array('LIKE', '%' . $_POST['content'] . '%');
        }
        //站内信列表
        $where['status'] = array('neq', 9);
        $where['fr_id'] = array('neq', 0);
        $message_list = $this->letter->selectLetter($where, 'ctime desc', '15');
        foreach ($message_list['list'] as $k => $v) {
            //找到发信人
            unset($where);
            $where['m_id'] = $v['fr_id'];
            $message_list['list'][$k]['company_name'] = M('member')->where($where)->getField('company_name');
        }
        unset($where);

        $data = $message_list['list'];
        foreach ($data as $k => $v) {
            //列表最多出现20个字
            $data[$k]['le_answer'] = $this->cut_str($v['le_answer'], 20);
            $data[$k]['le_content'] = $this->cut_str($v['le_content'], 20);
        }
        $this->assign('messagelist', $data);
        $this->assign('page', $message_list['page']);
        $this->display('messageList');

    }

    /**
     *  回复邮件
     */
    public function messageInfo()
    {
        $this->checkAuth('Letter', 'messageInfo');
        if (!$_POST) {
            $where['le_id'] = $_GET['le_id'];
            $where['status'] = array('neq', 9);
            //找到邮件
            $data = $this->letter->findLetter($where);


            //textarea与HTML 内容转换
            if (strpos($data['le_content'], '<br>')) {
                //删除既有的br
                $data['le_content'] = str_replace("<br>", "", $data['le_content']);
            }
            //textarea与HTML 内容转换
            if (strpos($data['le_answer'], '<br>')) {
                //删除既有的br
                $data['le_answer'] = str_replace("<br>", "", $data['le_answer']);
            }
            //项目的名称
            unset($where);
            $where['t_id'] = $data['t_id'];
            $data['t_name'] = M('task')->where($where)->getField('name');
            unset($where);
            //找到发信人
            $where['m_id'] = $data['fr_id'];
            $member = $this->member->findMember($where);
            if ($member) {
                $newdata['f_name'] = $member['company_name'];
                $this->assign('data', $data);
                $this->assign('newdata', $newdata);
                $this->display('messageInfo');
            } else {
                $this->error('无信息');
            }
        } else {
            $where['le_id'] = $_POST['le_id'];
            $where['status'] = array('neq', 9);
            $data['le_answer'] = $_POST['le_answer'];
            $data['is_read'] = 1;
            $data['utime'] = time();
            $edit_res = $this->letter->editLetter($where, $data);
            if ($edit_res) {
                $this->success('回复邮件成功', U('Letter/messageList'));
            } else {
                $this->error('回复失败');
            }

        }

    }

    /**
     *  发送邮件
     */
    public function addOne()
    {
        $this->checkAuth('Letter', 'addOne');
        if (empty($_POST)) {
            $where['status'] = array('neq', 9);
            $member = $this->member->selectMember($where, 'ctime desc', '');
            $task_list = M('task')->where($where)->select();
            $this->assign('task_list', $task_list);
            $this->assign('member', $member);
            $this->display('addOne');
        } else {
            $data = $this->member->create();
            if ($data) {
                //系统发送邮件视为 回复 收信人的邮件  发信人为服务商，收信人为系统 0。否则就变成广播了
                $newdata['fr_id'] = $_POST['to_id'];
                $newdata['to_id'] = '0';
                $newdata['t_id'] = $_POST['t_id'];
                $newdata['ctime'] = time();
                $newdata['utime'] = time();
                $newdata['status'] = '1'; //1为后台主动发送的邮件
                //$newdata['le_content'] = '大创意邮件';


                //替换掉 换行
                $newdata['le_answer'] = str_replace("\n", "<br>", $_POST['le_content']);
                //替换掉 空格
                $newdata['le_answer'] = str_replace(" ", "&nbsp;", $newdata['le_answer']);

                //$newdata['le_answer'] = $_POST['le_content'];

                //系统已回复
                $newdata['is_read'] = 1;
                $add_res = $this->letter->addLetter($newdata);
                if ($add_res) {
                    $this->success('发送邮件成功', U('Letter/messageList'));
                } else {
                    $this->error('发送邮件失败');
                }
            } else {
                $this->error($this->letter->getError());
            }
        }

    }

    /**
     * 字符串截取函数
     */
    public function cut_str($sourcestr, $cutlength)
    {
        $returnstr = '';
        $i = 0;
        $n = 0;
        $str_length = strlen($sourcestr);//字符串的字节数
        while (($n < $cutlength) and ($i <= $str_length)) {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = Ord($temp_str);//得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224)    //如果ASCII位高与224，
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3;            //实际Byte计为3
                $n++;            //字串长度计1
            } elseif ($ascnum >= 192) //如果ASCII位高与192，
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2;            //实际Byte计为2
                $n++;            //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) //如果是大写字母，
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数仍计1个
                $n++;            //但考虑整体美观，大写字母计成一个高位字符
            } else                //其他情况下，包括小写字母和半角标点符号，
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数计1个
                $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > $i) {
            $returnstr = $returnstr . "...";//超过长度时在尾处加上省略号
        }
        return $returnstr;
    }

    public function sendEmail()
    {
        $this->display('sendEmail');
    }

    public function doSendEmail()
    {
        $aim = $_POST['le_aim'];
        $to_list = array();
        switch($aim) {
            case '所有用户':
                /*$mem = $this->mem_obj->selectMember2();
                foreach ($mem as $m) {
                    if (!empty($m['m_email'])) {
                        array_push($to_list, $m['m_email']);
                    }
                }*/
                break;
            case '在线用户':
                /*$mem = $this->mem_obj->selectMember2();
                foreach($mem as $m) {
                    if (!empty($m['m_email'])) {
                        array_push($to_list, $m['m_email']);
                    }
                }*/
                break;
            case '不在线用户':

                break;
            case '实名用户':
                /*$where["status"] = 2;
                $mem = $this->mem_obj->selectMember2($where);
                foreach ($mem as $m) {
                    if (!empty($m['m_email'])) {
                        array_push($to_list, $m['m_email']);
                    }
                }
                break;*/
            case '未实名用户':
                /*$where["status"] = array('neq', 2);
                $mem = $this->mem_obj->selectMember2($where);
                foreach ($mem as $m) {
                    if (!empty($m['m_email'])) {
                        array_push($to_list, $m['m_email']);
                    }
                }*/
                break;
            case '自定义':
                /*$where["status"] = array('neq', 2);
                $mem = $this->mem_obj->selectMember2($where);
                foreach ($mem as $m) {
                    if (!empty($m['m_email'])) {
                        array_push($to_list, $m['m_email']);
                    }
                }*/
                break;
        }
        $to_list = array('1131762828@qq.com','960346496@qq.com');
        if(empty($to_list)){
            $this->error("{$aim}的总人数为0");
        }else {
            $le_title = $_POST['le_title'];
            $le_content = $_POST['le_content'];
            if(!$le_title){
                $this->error("主题不能为空");
            }
            if(!$le_content){
                $this->error("内容不能为空");
            }
            $res = $this->email_obj->sendEmailBatch($to_list, $le_title, $le_content);
            if ($res) {
                $this->success("邮件批量发送成功，数量为：" . count($to_list));
            } else {
                $this->error("邮件发送失败，请重试");
            }
        }
    }
}