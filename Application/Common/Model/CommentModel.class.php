<?php
namespace Common\Model;

use Think\Model;

/**
 * Class CommentModel
 * @package Common\Model
 */
class CommentModel extends Model
{
    protected $tableName = 'task_idea_comment';
    /**
     * @var array自动验证
     */
    protected $_validate =array(
        array('idea_id','require','数据请求错误！'),
        array('content','require','评论内容不能为空！'),
    );
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array(
        array('ctime', 'time', self::MODEL_INSERT, 'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime', 'time', self::MODEL_BOTH, 'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    /**
     * 查询多条数据
     */
    public function selectComment2($where = array(), $order = '', $page_size = '', $parameter = array())
    {
        if ($where['status'] == '' || empty($where['status'])) {
            $where['status'] = array('neq', 9);
        }
        if ($page_size == '') {
            $result = $this->where($where)->order($order)->select();
        } else {
            $count = $this->where($where)->count();
            $page = new \Think\Page($count, $page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme', $this->setPageTheme());
            $page_info = $page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow, $page_size)
                ->select();
            $result = array('page' => $page_info, 'list' => $list);
        }
        return $result;
    }

    /**
     * 查询多条数据
     */
    public function selectComment($where = array(),$order = '',$page_size = 2,$page_index = 0){
        $comment = $this->getTableName();
        $mem_obj = D("Member");
        $member = $mem_obj->getTableName();
        if($where["$comment.status"] == ''|| empty($where["$comment.status"])){
            $where["$comment.status"] = array('neq',9);
        }

        $field = "comment_id,$member.m_id,to_mid,content,$comment.utime,m_head,m_account";
        $list = $this->field($field)
            ->where($where)
            ->join("LEFT JOIN $member ON $member.m_id = $comment.m_id")
            ->order($order)->limit($page_index * $page_size,$page_size)->select();

        foreach($list as $k=>$v){
            $list[$k]['time'] = date('Y-m-d H:i:s',$v['utime']);
            unset($where);
            $where['m_id'] = $v['m_id'];
            $mem = $mem_obj->findMember($where);
            $list[$k]['m_account'] = $mem['m_account'];
            $list[$k]['head'] = $mem['m_head'];
            if($v['to_mid']){
                unset($where);
                $where['comment_id'] = $v['to_mid'];
                $com = $this->field('m_id')->findComment($where);
                if($com) {
                    unset($where);
                    $where['m_id'] = $com['m_id'];
                    $mem = $mem_obj->findMember($where);
                    if($mem) {
                        $list[$k]['to_id'] = $com['m_id'];
                        $list[$k]['to_account'] = $mem['m_account'];
                    }
                }
            }
        }
        return $list;
    }

    /*
* 将阅读状态设置为1
*/
    public function setIsRead($id_list){
        $where['comment_id'] = array('in',$id_list);
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
    public function selectCommentMessage($uid,$page_size = '',$where = array(),$order = '',$parameter = array()){
        $comment = $this->getTableName();
        $idea = D("Idea")->getTableName();
        if(empty($uid)){
            return false;
        }
        if($where['status'] == ''|| empty($where['status']) || $where['is_read'] == ''|| empty($where['is_read'])){
            $where["$comment.status"] = array('neq',9);
            $where["$comment.is_read"] = array('neq',9);
        }
        $field = "$comment.comment_id,$comment.is_read,$comment.m_id,$comment.idea_id,$comment.content,$comment.ctime,$idea.task_id,$idea.m_id uid";
        if($page_size == ''){
            $result = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$comment.idea_id AND $idea.m_id=$uid")
                ->order($order)->select();
        }else{
            $count = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$comment.idea_id AND $idea.m_id=$uid")
                ->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$comment.idea_id AND $idea.m_id=$uid")
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
    public function addComment($data)
    {
        if (empty($data)) {
            return false;
        } else {
            if($this->create($data)){
                $res = $this->add();
                return $res;
            }
            return false;
        }
    }

    /**
     * 查询一条数据
     */
    public function findComment($where)
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
    public function editComment($where, $data)
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
    public function deleteComment($where)
    {
        if (empty($where)) {
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }
    /*
     * 统计未读评论的条数
     */
    public function countRead($uid){
        $sql = "SELECT COUNT(*) num FROM toocms_task_idea_comment JOIN toocms_task_idea ON toocms_task_idea_comment.idea_id=toocms_task_idea.idea_id WHERE toocms_task_idea.m_id=$uid AND toocms_task_idea_comment.is_read=0";
        $result = $this->query($sql);
        $result = $result[0]['num'];
        return $result;
    }

    /**
     * 分页样式
     */
    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }
}