<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-2-9
 * Time: 下午2:43
 */
namespace Common\Model;
use Think\Model;

/**
 * Class LetterModel
 * @package Common\Model
 */
class LetterModel extends Model {
    protected $tableName = 'letter';

    protected $_validate = array(
        array('le_content','require','站内信内容不能为空！'), //空验证  默认情况下用正则进行验证
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
    public function selectLetter($where = array(),$order = '',$page_size = '',$parameter = array()){
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
     * 查询多条数据
     */
    public function selectLetterMessage($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['status'] == ''|| empty($where['status']) || $where['msg_status'] == ''|| empty($where['msg_status'])){
            $where['status'] = array('neq',9);
            $where['msg_status'] = array('neq','删除');
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
    public function addLetter($data){
        if(empty($data)){
            return false;
        }else{
            $this->create($data);
            $result = $this->add();
            return $result;
        }
    }
    /*
 * 将阅读状态设置为1
 */
    public function setIsRead($id_list){
        $where['le_id'] = array('in',$id_list);
        $data['msg_status'] = '已读';
        if(empty($data) || empty($where)){
            return false;
        }else{
            if($res = $this->where($where)->save($data)){
                return $res;
            }
            return false;
        }
    }

    /*
* 将阅读状态设置为9,也就是删除站内信
*/
    public function delMsg($where){
        $data['msg_status'] = '删除';
        if(empty($data) || empty($where)){
            return false;
        }else{
            if($res = $this->where($where)->save($data)){
                return $res;
            }
            return false;
        }
    }
    /**
     * 查询一条数据
     */
    public function findLetter($where){
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
    public function editLetter($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteLetter($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }
 /*
 * 统计未读站内信条数
 */
    public function countRead($uid,$t){
        $sql = "SELECT COUNT(*) num FROM toocms_letter WHERE (to_id=$uid OR to_id=0) AND msg_status='未读'";
        if($t){
            $sql.= " AND ctime > $t";
        }
        $result = $this->query($sql);
        $result = $result[0]['num'];
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

    //获取互动消息，包括：点赞、评论。
    public function getHuDongMsg($uid,$page_size = 10,$where = array(),$order = '',$parameter = array())
    {
        if($uid){
            if(!$order) $order = 'M.ctime DESC';
            $member = D("Member");
            $memberN = $member->getTableName();
            $idea = D("Idea")->getTableName();
            $task = D("Task")->getTableName();
            $comment = D("Comment")->getTableName();
            $ideaL = D("TaskIdeaLike")->getTableName();
            $taskL = D("TaskLike")->getTableName();
            /*$sql = "select idea_id as id,$idea.ctime,1 as flag,$idea.msg_status,$idea.m_id as mid from $idea,$task
                WHERE $idea.task_id = $task.t_id AND $task.m_id = $uid AND msg_status <> '删除'

                union all
                select A.comment_id as id,A.ctime,2 as flag,A.msg_status,A.m_id as mid from $comment A,$idea,$comment B
                WHERE ((A.to_mid = 0 AND A.idea_id = $idea.idea_id AND $idea.m_id = $uid) OR (A.to_mid = B.comment_id AND B.m_id = $uid)) AND A.msg_status <> '删除'

                union all
                select like_id as id,$ideaL.ctime,3 as flag,$ideaL.msg_status,$ideaL.m_id as mid from $ideaL,$idea
                WHERE $ideaL.idea_id = $idea.idea_id AND $idea.m_id = $uid AND $ideaL.msg_status <> '删除'

                union all
                select like_id as id,$taskL.ctime,4 as flag,$taskL.msg_status,$taskL.m_id as mid from $taskL,$task
                WHERE $taskL.task_id = $task.t_id AND $task.m_id = $uid AND msg_status <> '删除'";
            $res = $this->query("SELECT COUNT(*) as sum FROM ($sql)M");
            $count = $res[0]['sum'];
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $member->field('m_account,id,M.ctime,flag,msg_status,mid')
                ->join("RIGHT JOIN($sql)M on (M.mid=$memberN.m_id)")->where($where)->order($order)->limit($page->firstRow,$page_size)->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
            return $result;*/
        }
    }

    public function getHuDongMsgCount($uid,$t){
        if($uid){
            $idea = D("Idea")->getTableName();
            $task = D("Task")->getTableName();
            $comment = D("Comment")->getTableName();
            $ideaL = D("TaskIdeaLike")->getTableName();
            $taskL = D("TaskLike")->getTableName();
            $wt = $t? " Where ctime > $t":"";
            /*$sql = "select COUNT(*) as sum FROM (
                select idea_id as id,$idea.ctime from $idea,$task
                WHERE $idea.task_id = $task.t_id AND $task.m_id = $uid AND msg_status = '未读'

                union all
                select A.comment_id as id,A.ctime from $comment A,$idea,$comment B
                WHERE ((A.to_mid = 0 AND A.idea_id = $idea.idea_id AND $idea.m_id = $uid) OR (A.to_mid = B.comment_id AND B.m_id = $uid)) AND A.msg_status = '未读'

                union all
                select like_id as id,$ideaL.ctime from $ideaL,$idea
                WHERE $ideaL.idea_id = $idea.idea_id AND $idea.m_id = $uid AND $ideaL.msg_status = '未读'

                union all
                select like_id as id,$taskL.ctime from $taskL,$task
                WHERE $taskL.task_id = $task.t_id AND $task.m_id = $uid AND msg_status = '未读'
                )M$t";
            $res = $this->query($sql);
            return $res[0]['sum'];*/
        }
    }

    //获取交易，包括：交易和询价通知。
    public function getJiaoYiMsg($uid,$page_size = 10,$where = array(),$order = '',$parameter = array())
    {
        if($uid){
            if(!$order) $order = 'M.time DESC';
            $tm = D("TaskMsg")->getTableName();
            $rm = D("DingdanMsg")->getTableName();
            //$dh = D("DepositHistory")->getTableName();
            //select deposit_history_id as id,key_id,amount as s1,status,type,$dh.utime as time,1 as flag,$dh.msg_status from $dh
            //WHERE $dh.m_id = $uid AND msg_status <> '删除'
            //union all
            $sql = "
            select msg_id as id,d_id as key_id,0 as s1,$rm.m_id as status,type,$rm.ctime as time,11 as flag,$rm.msg_status from $rm
                WHERE $rm.to_mid = $uid AND msg_status <> '删除'

            union all
            select msg_id as id,t_id as key_id,0 as s1,tag as status,type,$tm.ctime as time,21 as flag,$tm.msg_status from $tm
                WHERE $tm.m_id = $uid AND msg_status <> '删除'";
            $res = $this->query("SELECT COUNT(*) as sum FROM ($sql)M");
            $count = $res[0]['sum'];
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this->table("($sql)M")->where($where)->order($order)->limit($page->firstRow,$page_size)->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
            return $result;
        }
    }

    public function getJiaoYiMsgCount($uid,$t){
        if($uid){
            $tm = D("TaskMsg")->getTableName();
            //$dh = D("DepositHistory")->getTableName();
            $rm = D("DingdanMsg")->getTableName();
            $wt = $t? " WHERE time > $t":"";

            //select deposit_history_id as id,utime as time from $dh
            //WHERE $dh.m_id = $uid AND msg_status = '未读'
            //union all


            $sql = "select COUNT(*) as sum FROM (
            select msg_id as id,ctime as time from $rm
                WHERE $rm.to_mid = $uid AND msg_status = '未读'

            union all

            select msg_id as id,$tm.ctime as time from $tm
                WHERE $tm.m_id = $uid AND msg_status = '未读'
                )M$wt";
            $res = $this->query($sql);
            return $res[0]['sum'];
        }
    }

    public function readMsg($type,$id){
        switch($type){
            case '1':
                $obj = D('DepositHistory');
                break;
            case '2':
                $obj = $this;
                break;
            case '3':
                $obj = D('Idea');
                break;
            case '4':
                $obj = D('Comment');
                break;
            case '5':
                $obj = D('TaskIdeaLike');
                break;
            case '6':
                $obj = D('TaskLike');
                break;
        }
        if($obj){
            $res = $obj->setIsRead($id);
        }
        return $res;
    }

}