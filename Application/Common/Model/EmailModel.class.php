<?php
namespace Common\Model;
use Think;
use Think\Model;


/**
 * Class SmsModel
 * @package Common\Model
 * 发送短信的Model
 */
class EmailModel extends Model
{

    protected $tableName = 'email';
    public $mem_obj = '';
    public $idea_obj = '';
    public $task_obj = '';

    private $invitation_obj = '';
    public $PATH;

    public function _initialize()
    {
        $this->invitation_obj = D('Invitation');
        $this->mem_obj = D('Member');
        $this->idea_obj = D('Idea');
        $this->task_obj = D('Task');
        $this->PATH = $this->getHostUrl()."/Email/";
    }

    public function getHostUrl()
    {
        if (C('APP_ENV') === 'test') {
            return "http://test.pitchina.com.cn/index.php";
        } else if (C('APP_ENV') === 'online') {
            return "http://www.pitchina.com.cn/index.php";
        } else {
            return "http://localhost/pitchina/index.php";
        }
    }

    public function generateInvitationUrl()
    {
        $number = $this->invitation_obj->generateInvitation();
        //存储数据
        $data['invitation'] = $number;
        $data['status'] = 0;
        $data['time'] = time();
        $res = $this->invitation_obj->addInvitation($data);
        //发送信息
        if ($res) {
            return $this->getHostUrl() . '/LoginRegister/yi_register/invitation/' . $number;
        } else {
            return 0;
        }
    }

    public function sendInvitation($email, $invitationUrl = '')
    {
        if ($invitationUrl == '') {
            $url = $this->generateInvitationUrl();
        } else {
            $url = $invitationUrl;
        }
        //发送信息
        if ($url) {
            $subject = '大创意邀请码，请点击下面的链接进行注册';
            $content = $url;
            $result = $this->sendEmail($email, '', $subject, $content);
            if ($result == true) {
                return array('flag' => '0', 'message' => '');
            }
        }
        return array('flag' => '1', 'message' => '失败');
    }

    //生成验证码
    function generateVerify($length = 6)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = '0123456789';
        $Verify = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            //if ($i != 0 && $i % 4 == 0) $password .= '_';
            $Verify .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $Verify;
    }

    //发送验证码
    public function sendVerify($email)
    {
        $verify = $this->generateVerify();
        $_SESSION['MAIL_VERIFY'] = $verify;
        //发送信息
        if ($verify) {
            $subject = '大创意验证码:';
            $content = $verify;
            $result = $this->sendEmail($email, '', $subject, $content);
            if ($result == true) {
                return array('flag' => '0', 'message' => '');
            }
        }
        return array('flag' => '1', 'message' => '失败');
    }

    /**
     * 发送邮件
     * @param $to  接受者邮箱
     * @param $name 接受者名称
     * @param string $subject 邮件标题
     * @param string $body 邮件内容 支持html
     * @param null $attachment 附件
     * @return bool|string
     */
    public function sendEmail($to, $name, $subject = '', $body = '', $attachment = null)
    {
        try {
            //$config = C('THINK_EMAIL');
            $config = D('Config')->findConfig();
            vendor('PHPMailer.class#phpmailer');      //从PHPMailer目录导class.phpmailer.php类文件
            $mail = new \Vendor\PHPMailer\PHPMailer(true); //PHPMailer对象
            $mail->CharSet = 'UTF-8';              //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                          // 设定使用SMTP服务
            $mail->SMTPAuth = true;                // 启用 SMTP 验证功能
            $mail->SMTPDebug = 1;                    // 关闭SMTP调试功能
            //$mail->SMTPSecure = 'ssl';              // 使用安全协议
            $mail->Host = 'smtp.exmail.qq.com'; // SMTP 服务器
            $mail->Port = 25; // SMTP服务器的端口号
            $mail->Username = 'service@pitchina.com.cn'; // SMTP服务器用户名
            $mail->Password = 'Pitchina2015'; // SMTP服务器密码
            //$replyEmail       = $config['REPLY_EMAIL'] ? $config['REPLY_EMAIL'] : $config['FROM_EMAIL'];//回复地址
            //$replyName        = $config['REPLY_NAME'] ? $config['REPLY_NAME'] : $config['FROM_NAME'];//回复名称
            //$mail->AddReplyTo($replyEmail,$replyName);
            $mail->From = 'service@pitchina.com.cn';
            $mail->FromName = '大创意';
            $mail->AddAddress($to, $name);
            $mail->Subject = $subject;//邮件标题
            $mail->AltBody = "";
            $mail->WordWrap = 80;
            // 添加附件
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    is_file($file) && $mail->AddAttachment($file);
                }
            }
            $mail->MsgHTML($body);//邮件主体
            $mail->IsHTML(true);
            return $mail->Send() ? true : $mail->ErrorInfo;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function sendEmailBatch($to_list, $subject = '', $body = '', $attachment = null)
    {
        if (empty($to_list)) return;
        //$config = C('THINK_EMAIL');
        $config = D('Config')->findConfig();
        vendor('PHPMailer.class#phpmailer');      //从PHPMailer目录导class.phpmailer.php类文件
        $mail = new \Vendor\PHPMailer\PHPMailer(true); //PHPMailer对象
        $mail->CharSet = 'UTF-8';              //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();                          // 设定使用SMTP服务
        $mail->SMTPAuth = true;                // 启用 SMTP 验证功能
        $mail->SMTPDebug = 1;                    // 关闭SMTP调试功能
        //$mail->SMTPSecure = 'ssl';              // 使用安全协议
        $mail->Host = 'smtp.exmail.qq.com'; // SMTP 服务器
        $mail->Port = 25; // SMTP服务器的端口号
        $mail->Username = 'service@pitchina.com.cn'; // SMTP服务器用户名
        $mail->Password = 'Pitchina2015'; // SMTP服务器密码
        //$replyEmail       = $config['REPLY_EMAIL'] ? $config['REPLY_EMAIL'] : $config['FROM_EMAIL'];//回复地址
        //$replyName        = $config['REPLY_NAME'] ? $config['REPLY_NAME'] : $config['FROM_NAME'];//回复名称
        //$mail->AddReplyTo($replyEmail,$replyName);
        $mail->From = 'service@pitchina.com.cn';
        $mail->FromName = '大创意';
        foreach ($to_list as $to) {
            $mail->AddAddress($to, '');
        }
        $mail->Subject = $subject;//邮件标题
        $mail->AltBody = "";
        $mail->WordWrap = 80;
        // 添加附件
        if (is_array($attachment)) {
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        $mail->MsgHTML($body);//邮件主体
        $mail->IsHTML(true);
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    //有人注册欢迎的邮件
    public function welcome($email)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email));
    }

    //发送验证邮箱邮件
    public function checkEmail($email, $url)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email, 'url' => $url));
    }

    //发送 催单 邮件
    public function cuidanEmail($email, $url)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email, 'url' => $url));
    }

    //找回密码的邮件
    public function resetPass($email, $url)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email, 'url' => $url));
    }

    //有人实名认证发邮件给管理员
    public function checkMem($name, $head)
    {
        $this->sockToSendEmail(__FUNCTION__, array('name' => $name, 'head' => $head));
    }

    //有成功付款的脑暴需要审核，给管理员发送邮件
    public function checkTask($taskname)
    {
        $this->sockToSendEmail(__FUNCTION__, array('taskname' => $taskname));
    }

    //脑洞举报审核通过，给举报者发送邮件
    public function dealIdeaMID($mid,$content)
    {
        $this->sockToSendEmail(__FUNCTION__, array('mid' =>$mid,'content'=>$content));
    }
    //脑洞举报审核通过，给被举报者发送邮件
    public function dealIdeaTOID($mid,$content)
    {
        $this->sockToSendEmail(__FUNCTION__, array('mid' =>$mid,'content'=>$content));
    }
    //有脑洞举报需要审核，给管理员发送邮件
    public function jubaoIdea($ideaid)
    {
        $this->sockToSendEmail(__FUNCTION__, array('ideaid' => $ideaid));
    }

    //有提现申请需要审核，给管理员发送邮件
    public function checkMoney()
    {
        $this->sockToSendEmail(__FUNCTION__);
    }

    //实名认证通过后发的邮件
    public function checkMemOk($email,$phone)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email,'phone' => $phone));
    }

    //实名认证不通过后发的邮件
    public function checkMemNo($email,$phone)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email,'phone' => $phone));
    }

    //提现成功后发的邮件
    public function tixianOk($email,$phone)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email,'phone' => $phone));
    }
    //提现未通过发的邮件
    public function tixianNo($email,$phone,$dh_id)
    {
        $this->sockToSendEmail(__FUNCTION__, array('email' => $email,'phone' => $phone, 'dh_id' => $dh_id));
    }

    //脑暴审核通过，发送邮件提醒
    public function sendTaskYes($tid)
    {
        $this->sockToSendEmail(__FUNCTION__, array('tid' => $tid));
    }

    //脑暴审核不通过，发送邮件提醒
    public function sendTaskNo($tid)
    {
        $this->sockToSendEmail(__FUNCTION__, array('tid' => $tid));
    }

    //task数据和分发奖金数据
    public function taskOver($task, $arr)
    {
        $this->sockToSendEmail(__FUNCTION__, array(
            'name' => $task['name'],
            'email' => implode(',', array_column($arr, 'm_email')),
            'phone' => implode(',', array_column($arr, 'm_phone')),
            'm_head' => implode(',', array_column($arr, 'm_head')),
            'bfb' => implode(',', array_column($arr, 'bfb')),
            'money' => implode(',', array_column($arr, 'money'))
        ));
    }

    private function sockToSendEmail($url, $data = NULL)
    {
        if (!$url) throw new Think\Exception("error：url不能为空");

        $url = "{$this->PATH}" . $url;
        $parse = parse_url($url);
        isset($parse['host']) || $parse['host'] = '';
        isset($parse['path']) || $parse['path'] = '';
        isset($parse['query']) || $parse['query'] = '';
        isset($parse['port']) || $parse['port'] = '';

        $path = $parse['path'] ? $parse['path'] . ($parse['query'] ? '?' . $parse['query'] : '') : '/';
        $host = $parse['host'];

        //协议
        if ($parse['scheme'] == 'https') {
            $version = '1.1';
            $port = empty($parse['port']) ? 443 : $parse['port'];
            $host = 'ssl://' . $host;
        } else {
            $version = '1.0';
            $port = empty($parse['port']) ? 80 : $parse['port'];
        }

        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        if (!$fp) {
            exit('Failed to establish socket connection: ' . $url);
        }

        //Headers
        $hs[] = "Host: {$parse['host']}";
        $hs[] = 'Connection: Close';
        $hs[] = "User-Agent: $_SERVER[HTTP_USER_AGENT]";
        $hs[] = 'Accept: */*';

        //包体信息
        if (!empty($data)) {
            if (is_array($data)) {
                $data = http_build_query($data);
            }
            $hs[] = "Content-type: application/x-www-form-urlencoded";
            $hs[] = 'Content-Length: ' . strlen($data);
            $out = "POST $path HTTP/$version\r\n" . join("\r\n", $hs) . "\r\n\r\n" . $data;
        } else {
            $out = "GET $path HTTP/$version\r\n" . join("\r\n", $hs) . "\r\n\r\n";
        }
        fwrite($fp, $out);
        // echo fread($fp, 3107); //我们不关心服务器返回
        fclose($fp);
    }

    /**
     * 添加数据
     */
    public function addEmail($data)
    {
        if (empty($data)) {
            return false;
        } else {
            $result = $this->data($data)->add();
            return $result;
        }
    }

    /**
     * 查询一条数据
     */
    public function findEmail($where)
    {
        if (empty($where)) {
            return false;
        } else {
            if ($where['status'] == '' || empty($where['status'])) {
                $where['status'] = array('neq', '9');
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }

    /**
     * 编辑数据
     */
    public function editEmail($where, $data)
    {
        if (empty($where) || empty($data)) {
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }

    /**
     * 删除数据
     */
    public function deleteEmail($where)
    {
        if (empty($where)) {
            return false;
        }
        $result = $this->where($where)->delete();
        return $result;
    }
}