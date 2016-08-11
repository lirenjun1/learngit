<?php
/**
 * Created by PhpStorm.
 * User: theonead
 * Date: 10/13 0013
 * Time: 10:40
 */
namespace Common\Model;
use Think\Model;

/*
 * Class IdeaModel
 * @package Common\Model
 */
class IdeaModel extends Model{
    protected $tableName = 'task_idea';

    /**
     * @var array自动验证
     */
    protected $_validate =array(
        array('task_id','require','数据请求错误！'),
        array('content','require','idea内容不能为空！'),
    );

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    //获取脑洞数
    public function countIdea($where){
        if(empty($where)){
            return false;
        }
        $where["status"] = array('lt',7);
        $result = $this->where($where)->count();
        return $result;
    }

    /**
     * 查询多条数据
     */
    public function selectIdea($where = array(),$order = '',$page_size = 6,$page_index = 0){
        $idea = $this->getTableName();
        $member = D("Member")->getTableName();
        //$comment = D("Comment")->getTableName();
        //$like = D("TaskIdeaLike")->getTableName();
        if($where["$idea.status"] == ''|| empty($where["$idea.status"])){
            $where["$idea.status"] = array('neq',9);
        }

        //个人信息          //评论数
        $field = "idea_id,task_id,content,$idea.money,image,$idea.ctime,$idea.m_id,$idea.status,m_account,m_email,m_head";
        /*if($u_id){//如果已登录
            $list = $this
                ->field($field . ",`like_id` islike")//过滤是否被当前用户收藏
            ->join("LEFT JOIN $like ON ($like.`m_id`=$u_id AND $like.`idea_id`= $idea.`idea_id` AND $like.status <> 9)");//查询是否被当前用户收藏
        }else{
            $list = $this->field($field);
        }*/

        $list = $this->field($field)
            ->where($where)
            ->join("LEFT JOIN $member M ON M.m_id=$idea.m_id") //查询发表Idea的人的个人信息
            /*->join("LEFT JOIN (
                SELECT COUNT(*)comment_n,`idea_id` id FROM $comment WHERE status <> 9 GROUP BY `idea_id`
                )C ON C.id=$idea.`idea_id`") //查询发表comment的人数 comment_n*/
            /*->join("LEFT JOIN (
                SELECT COUNT(*)like_n,`idea_id` id FROM $like WHERE status <> 9 GROUP BY `idea_id`
                )L ON L.id=$idea.`idea_id`") //查询点赞的人数*/
            ->order($order)->limit($page_index * $page_size,$page_size)->select();
        return $list;
    }

    public function selectIdeas($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('lt',8);
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

    //查询发言数前三名
    public function selectCountTop3($task_id){
        if(!$task_id)return;
        $where['task_id'] = $task_id;
        $idea = $this->getTableName();
        if($where["$idea.status"] == ''|| empty($where["$idea.status"])){
            $where["$idea.status"] = array('lt',8);
        }
        $member = D("Member")->getTableName();
        $res = $this->field("count(*) as n,$idea.m_id,m_account,m_head,m_email,$member.status")
            ->join("RIGHT JOIN $member ON $member.m_id=$idea.m_id") //查询发表Idea的人的个人信息
            ->where($where)->group('m_id')->order('n desc')->limit(3)->select();
        return $res;
    }

    //查询参与用户
    public function selectCountTop($task_id){
        if(!$task_id)return;
        $where['task_id'] = $task_id;
        $idea = $this->getTableName();
        if($where["$idea.status"] == ''|| empty($where["$idea.status"])){
            $where["$idea.status"] = array('lt',8);
        }
        $member = D("Member")->getTableName();
        $res = $this->field("count(*) as n,$idea.m_id,$idea.task_id,$member.status,$member.m_email,m_phone,$member.m_head")
            ->join("RIGHT JOIN $member ON $member.m_id=$idea.m_id") //查询发表Idea的人的个人信息
            ->where($where)->group('m_id')->order('n desc')->select();
        return $res;
    }

    //Idea提醒
    public function selectIdeaMessage($uid,$page_size = '',$where = array(),$order = '',$parameter = array()){
        $idea = $this->getTableName();
        $task = D("Task")->getTableName();
        if(empty($uid)){
            return false;
        }
        if($where['is_read'] == ''|| empty($where['is_read'])){
            $where["$idea.is_read"] = array('neq',9);
        }
        $field = "$idea.idea_id,$idea.is_read,$idea.m_id,$idea.task_id,$idea.content,$idea.ctime,$task.m_id uid";
        if($page_size == ''){
            $result = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $task ON $task.t_id=$idea.task_id AND $task.m_id=$uid")
                ->order($order)->select();
        }else{
            $count = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $task ON $task.t_id=$idea.task_id AND $task.m_id=$uid")
                ->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $task ON $task.t_id=$idea.task_id AND $task.m_id=$uid")
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
    public function selectIdea2($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('lt',8);
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
    public function addIdea($data)
    {
        if (empty($data)) {
            return false;
        } else {
            if ($this->create($data)) {
                $res = $this->add();
                return $res;
            }
            return false;
        }
    }

    /*
* 将阅读状态设置为1
*/
    public function setIsRead($id_list){
        $where['idea_id'] = array('in',$id_list);
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
    public function findIdea($where){
        if(empty($where)){
            return false;
        }else{
            if($where['status'] == '' || empty($where['status'])){
                $where['status'] = array('lt',8);
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }
    /**
     * 编辑数据
     */
    public function editIdea($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteIdea($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }

    /**
     * 删除非法数据
     */
    public function deleteReportIdea($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 8;
        $result = $this->where($where)->save($data);
        return $result;
    }

    //获取idea的条数
    public function getIdeaCount($where){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('lt',8);
        }
        $res = $this->where($where)->count();
        return $res;
    }

    //获取某个player参与的task个数
    public function getTaskCount($m_id)
    {
        $result = $this->table("(SELECT task_id FROM {$this->getTableName()} WHERE m_id = $m_id GROUP BY task_id) M")->count();
        return $result[0];
    }

    /*
 * 统计未读脑洞的条数
 */
    public function countRead($uid){
        $sql = "SELECT COUNT(*) num FROM toocms_task_idea JOIN toocms_task ON toocms_task.t_id=toocms_task_idea.task_id WHERE toocms_task.m_id=$uid AND toocms_task_idea.is_read=0";
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

}