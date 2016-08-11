<?php
namespace Common\Model;
use Think\Model;
/**
 * Class SkillTypeModel
 * @package Common\Model
 */
class SkillTypeModel extends Model {
    protected $tableName = 'member_skill_type';
    /**
     * @var array
     * 自动验证   使用create()方法时自动调用
     */
    protected $_validate = array(
        array('sk_name','require','合作机构名称不能为空！'), //空验证  默认情况下用正则进行验证
    );
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    /**
     * 查询多条数据
     */
        public function selectSkillType($where = array(),$order = '',$page_size = '',$parameter = array()){
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
            $result = array('page'=>$page_info,'list'=>$list);
        }
        return $result;
    }
    /**
     * 添加数据
     */
    public function addSkillType($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
    /**
     * 查询一条数据
     */
    public function findSkillType($where){
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
    public function editSkillType($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteSkillType($where){
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

    /**
     * 查询所有的技能
     */
    public function findFullSkill($where){
        $skilltype = $this->getTableName();
        $member = D('member')->getTableName();
        $result = $this->query("SELECT GROUP_CONCAT(sk_name)sks,m_id id FROM $skilltype,$member WHERE FIND_IN_SET(sk_id,common_skill) AND m_id=$where GROUP BY m_id");
        foreach($result as $v){
            $skill = $v['sks'];
        }
        return $skill;
    }
}