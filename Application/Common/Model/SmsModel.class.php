<?php
namespace Common\Model;
use Think\Model;

/**
 * Class SmsModel
 * @package Common\Model
 * 发送短信的Model
 */
class SmsModel extends Model{
    public $verify_obj = '';
    public $config;
    public $AdminPhone = '13911456583';        //管理员手机号
    public function _initialize(){
        $this->verify_obj = M('Sms');
        $this->config=array(
            //短信平台配置信息
            'switch'		=>C('SMS_SWITCH'),//短信发送服务开关
            'username'		=>C('SMS_USERNAME'),//用户名
            'password'		=>C('SMS_PASSWORD'),//密码
            'cpid'			=>C('SMS_CPID'),//用户的cpid
            'channel'		=>C('SMS_CHANNEL'),//通信信道id
            'checkMobile'	=>C('MOBILE'),//手机号验证正则表达式
            //验证码配置
            'verify_switch'	=>C('VERIFY_SWITCH'),//验证码开关
            'template'		=>C('VERIFY_TEMPLATE'),//信息模版
            'count'			=>C('VERIFY_COUNT'),//一天验证次数限制
            //调试
            'sms_debug'		=>'off',
        );
    }

    /**
     * @param $phone
     * @param $content
     * @return array
     */
    public function sendSms($phone,$content){
        //验证短信开关
        if($this->config['switch']!='on')return array('flag'=>'error','message'=>'短信发送服务未打开');
        //验证号码
        //if(!preg_match($this->config['checkMobile'],$phone))return array('flag'=>'error','message'=>'非法的手机号码');
        $password=md5($this->config['password']."_".time()."_topsky");
        //生成Url
        $sms_url="http://admin.sms9.net/houtai/sms.php?";
        $sms_content=urlencode(iconv("UTF-8","gb2312//ignore",$content.'如非本人操作请忽略。'));
        $url=$sms_url."cpid=".$this->config['cpid']."&password=$password&msg=$sms_content&tele=$phone&channelid=".$this->config['channel']."&timestamp=".time();
        //发送短信
        var_dump($url);
        if($this->config['sms_debug']=='on')return array('flag'=>'success','message'=>'信息已送达');
        $res_content = file_get_contents($url);
        $sms_respones = split(":","$res_content");
        if($sms_respones[0]=="error"){
            return array('flag'=>'error','message'=>'发送错误:'.$res_content);
        }else{
            return array('flag'=>'success','message'=>'验证码已发送，请注意查收');
        }
    }
    /**
     * @param $phone
     * @return array
     */
    public function sendVerify($phone){
        //验证短信开关
        if($this->config['verify_switch']!='on')return array('flag'=>'error','message'=>'短信验证服务未打开');
        //验证号码
        //if(!preg_match($this->config['checkMobile'],$phone))return array('flag'=>'error','message'=>'非法的手机号码');
        //验证申请次数
        $info=$this->verify_obj->where(array('phone'=>$phone))->find();
        if(!empty($info)){
            if(date("Ymd",time())==date("Ymd",$info['time'])){
                if($info['count']>=$this->config['count']){
                    return array('flag'=>'error','message'=>'一个手机号码一天内只能申请'.$this->config['count'].'次');
                }
            }else{
                $this->verify_obj->where(array('phone'=>$phone))->save(array('count'=>0));
            }
        }
        //随机生成验证码
        $number=rand(10,9999);
        $verify=rand(100000,999999);
        //存储数据
        $data['number'] = $number;
        $data['verify'] = $verify;
        $data['phone'] = $phone;
        $data['time'] = time();
        if(empty($info)){
            $res=$this->verify_obj->add($data);
            var_dump('aaa');
        }else{
            //设置今天发送短信次数加一
            $this->verify_obj->where(array('phone'=>$phone))->setInc('count',1);
            $res=$this->verify_obj->where(array('phone'=>$phone))->save($data);
        }
        //发送信息
        if($res){
            $content=$this->config['template'].$verify.'，';
            $res=$this->sendSms($phone,$content);
            return $res;
        }else{
            return array('flag'=>'error','message'=>'验证码生成失败');
        }
    }

    /**
     * @param $phone
     * @param $verify
     * @return bool
     */
    public function checkSmsVerify($phone,$verify){
        $info=$this->verify_obj->where(array('phone'=>$phone))->find();
        if($verify==$info['verify']){
            return true;
        }else{
            return false;
        }
    }
}