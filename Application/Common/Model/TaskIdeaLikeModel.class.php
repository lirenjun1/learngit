<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2015/11/11
 * Time: 13:48
 */

namespace Common\Model;
use Think\Model;

class TaskIdeaLikeModel extends Model{
    protected $tableName = 'task_idea_like';

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
    public function selectLike($where = array(),$order = '',$page_size = '',$parameter = array()){
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

    //Idea点赞信息查询
    public function selectIdeaLikeMessage($uid,$page_size = '',$where = array(),$order = '',$parameter = array()){
        $ideaLike = $this->getTableName();
        $idea = D("Idea")->getTableName();
        if(empty($uid)){
            return false;
        }
        if($where['status'] == ''|| empty($where['status']) || $where['is_read'] == ''|| empty($where['is_read'])){
            $where["$ideaLike.status"] = array('neq',9);
            $where["$ideaLike.is_read"] = array('neq',9);
        }
        $field = "$ideaLike.like_id,$ideaLike.m_id,$ideaLike.is_read,$ideaLike.idea_id,$ideaLike.ctime,$idea.task_id,$idea.m_id uid";
        if($page_size == ''){
            $result = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$ideaLike.idea_id AND $idea.m_id=$uid")
                ->order($order)->select();
        }else{
            $count = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$ideaLike.idea_id AND $idea.m_id=$uid")
                ->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this
                ->field($field)
                ->where($where)
                ->join("JOIN $idea ON $idea.idea_id=$ideaLike.idea_id AND $idea.m_id=$uid")
                ->order($order)
                ->limit($page->firstRow,$page_size)
                ->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
        }
        return $result;
    }
    /**
     * 添加赞
     */
    public function addLike($data){
        if(empty($data)) return false;

        if($this->create(array('status'=>0),2)) {
            $result = $this->where($data)->save();
            if($result) return true;
        }
        if($this->create($data)){
            $result = $this->add();
            return $result;
        }
        return false;
    }

    /*
* 将阅读状态设置为1
*/
    public function setIsRead($id_list){
        $where['like_id'] = array('in',$id_list);
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
     * 添加移除赞
     */
    public function cancelLike($where){
        if(empty($where)) return false;

        $data['status'] = 9;
        if($this->create($data,2)) {
            $result = $this->where($where)->save();
            return $result;
        }
        return false;
    }

    /**
     * 查询一条数据
     */
    public function findLike($where){
        if(empty($where)) return false;

        if($where['status'] == '' || empty($where['status'])){
            $where['status'] = array('neq','9');
        }
        $result = $this->where($where)->find();
        return $result;
    }
    /*
* 统计未读脑洞点赞的条数
*/
    public function countRead($uid){
        $sql = "SELECT COUNT(*) num FROM toocms_task_idea_like JOIN toocms_task_idea ON toocms_task_idea.idea_id=toocms_task_idea_like.idea_id WHERE toocms_task_idea.m_id=$uid AND toocms_task_idea_like.is_read=0";
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

    public function getLikeCount($where){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('neq',9);
        }
        return $this->where($where)->count();
    }

}