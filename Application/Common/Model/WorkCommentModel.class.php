<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2016/2/19
 * Time: 11:58
 */

namespace Common\Model;
use Think\Model;


class WorkCommentModel extends Model{
    protected $tableName = 'work_comment';

    /**
     * @var array自动验证
     */
    protected $_validate =array(
        array('work_id','require','数据请求错误！'),
        array('content','require','留言内容不能为空！'),
    );

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
    );

    /**
     * 查询多条数据
     */
    public function selectComment($where = array(),$order = '',$page_size = 6,$page_index = 0,$u_id){
        $comment = $this->getTableName();
        $member = D("Member")->getTableName();
        //$like = D("TaskIdeaLike")->getTableName();
        if($where["$comment.status"] == ''|| empty($where["$comment.status"])){
            $where["$comment.status"] = array('neq',9);
        }

        $field = "comment_id,work_id,content,$comment.ctime,$comment.m_id,$comment.to_cid,m_account,m_head,m_email";
        /*if($u_id){//如果已登录
            $list = $this
                ->field($field . ",`like_id` islike")//过滤是否被当前用户收藏
                ->join("LEFT JOIN $like ON ($like.`m_id`=$u_id AND $like.`idea_id`= $comment.`idea_id` AND $like.status <> 9)");//查询是否被当前用户收藏
        }else{
            $list = $this->field($field);
        }*/
        $list = $this->field($field);
        $list = $list
            ->where($where)
            ->join("LEFT JOIN $member M ON M.m_id=$comment.m_id") //查询发表Idea的人的个人信息
            /*->join("LEFT JOIN (
                SELECT COUNT(*)like_n,`idea_id` id FROM $like WHERE status <> 9 GROUP BY `idea_id`
                )L ON L.id=$comment.`idea_id`") //查询点赞的人数*/
            ->order($order)->limit($page_index * $page_size,$page_size)->select();
        return $list;
    }

    public function addComment($data){
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

    public function deleteComment($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }

    public function findComment($where){
        if(empty($where)){
            return false;
        }
        if($where["status"] == ''|| empty($where["status"])){
            $where["status"] = array('neq',9);
        }
        $result = $this->where($where)->find();
        return $result;
    }

    public function getCount($where){
        if(empty($where)){
            return false;
        }
        if($where["status"] == ''|| empty($where["status"])){
            $where["status"] = array('neq',9);
        }
        $result = $this->where($where)->count();
        return $result;
    }
}