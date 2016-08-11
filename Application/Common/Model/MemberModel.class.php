<?php
namespace Common\Model;
use Think\Model;
/**
 * Class MemberModel
 * @package Common\Model
 */
class MemberModel extends Model {
    protected $tableName = 'member';

    /**
     * @var array自动验证
     */
    protected $_validate =array(
        //array('m_account','m_account','格式不正确，请用邮箱格式'),
        array('m_email','email','请输入有效的邮箱！'),
        array('m_email','email','您的邮箱已注册，请找回密码或者更换邮箱。',0,'unique',1),

        array('m_phone','/^1[34578]\d{9}$/','请输入有效的手机号码！'),
        array('m_phone','email','您的手机号已注册，请找回密码或者更换手机号码。',0,'unique',1),

        array('m_password','/.{6,}/','密码不能少于6位'),
        array('m_account','/.{2,16}/','昵称2到16个字符！'),
//        array('m_account','','登录名已经被注册',0,'unique',1),
        array('money','number','请输入合法的身价(纯数字)，单位：元/小时'),

        array('region_id','require','地址不能为空'),
        array('region_id','number','请输入合法的地址'),

        array('other_skill','require','专长不能为空！'),
        array('alipayID','/^(1[34578]\d{9})|([A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5})$/','请输入有效的支付宝账号！'),
        array('card_num','/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/','请输入有效的身份证号！'),
    );

    function checkMoney($data){
        $money = intval($data, 10);
        return $money>0;
    }
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('m_password','md5',self::MODEL_INSERT,'function'),  //自动转MD5
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    /**
     * 查询多条数据
     */
    public function selectMember($where = array(),$filter,$order = '',$page_size = 8,$user = '',$page_index = 0){
        $member = $this->getTableName();
        $observe = D("Observe")->getTableName();
        $skilltype = D("SkillType")->getTableName();

        if($where["$member.status"] == ''|| empty($where["$member.status"])){
            $where["$member.status"] = array('neq',9);
        }
        $field = "m_id,money,region_id,m_account,m_email,m_head,fans_n,sks,$member.status";

        if($user != '') {
            $list = $this->field($field . ",$observe.`observe_id` isobs")//过滤是否被当前用户收藏
            ->join("LEFT JOIN $observe ON (`observer_id`=$user AND `observed_id`= `m_id` AND $observe.status <> 9)");//查询是否被当前用户收藏
        }
        else {//未登陆不查询是否关注了当前列表里的用户
            $list = $this->field($field);
        }
        $list = $list->where($where)
            ->join("LEFT JOIN (
            SELECT GROUP_CONCAT(`sk_name`)sks,m_id id FROM $skilltype,$member WHERE FIND_IN_SET(`sk_id`,`common_skill`) GROUP BY m_id
            )K ON K.id=`m_id`")//查询技能
            ->join("LEFT JOIN (
            SELECT COUNT(*)fans_n,`observed_id` id FROM $observe WHERE status <> 9 GROUP BY `observed_id`
            )F ON F.id=`m_id`")//查询收藏他的人数
            ->order($order)->limit($page_index * $page_size,$page_size)->select();
        return $list;
    }

    /**
     * 查询多条数据,用于后台
     */
    public function selectMember2($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('neq',9);
        }
        if($page_size == ''){
            $result = $this->where($where)->order($order)->select();
        }else{
            $count = $this->where($where)->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow,$page_size)
                ->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
        }
        return $result;
    }

    /**
     * 添加数据
     */
    public function addMember($data){
        if(empty($data)){
            return false;
        }else{
            if($this->create($data)){
                $result = $this->add();
                return $result;
            }else{
                return false;
            }
        }
    }
    /*
     * 更新数据
     */
    public function saveMember($where=array(),$data){
        if(empty($data)){
            return false;
        }else{
            if($this->create($data,2)) {
                $result = $this->where($where)->save();
                return $result;
            }
            return false;
        }
    }
    /**
     * 查询一条数据
     */
    public function findMember($where){
        if(empty($where)){
            return false;
        }else{
            if($where['status'] == '' || empty($where['status'])){
                $where['status'] = array('neq','9');
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }
    /**
     * 编辑数据
     */
    public function editMember($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteMember($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }
    //真的删除数据
    public function delMember($where){
        if(empty($where)){
            return false;
        }
        $result = $this->where($where)->delete();
        return $result;
    }

    /**
     * 分页样式
     */
    private function setPageTheme(){
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }

    /**
     * 数据相关处理
     * 当许多地方需要将取出的原数据惊醒改变格式 或添加某些相关数据
     */
    public function manageAdminInfo(){

    }

}