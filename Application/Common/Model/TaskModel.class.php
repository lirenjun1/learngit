<?php
namespace Common\Model;
use Think\Model;

/**
 * Class TaskModel
 * @package Common\Model
 */
class TaskModel extends Model
{
    protected $tableName = 'task';
    /**
     * @var array自动验证
     */
    protected $_validate = array(
        array('m_id', 'require', '任务发布人不能为空！'),
        array('brief', 'require', '脑暴描述不能为空！'),
        array('back_info', 'require', '背景介绍不能为空！'),
        array('picture', 'require', '脑暴头图不能为空！'),
        array('detail_require', 'require', '具体需求不能为空！'),
        array('aim', 'require', '期望目标不能为空！'),
        array('money', 'checkMoney', '请输入有效金额！', 0, 'callback', 1),  //使用回调函数checkCode
    );

    protected function checkMoney($money)
    {
        return is_numeric($money) && $money >= 1;
    }

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
    public function selectTask($where = array(), $order = '', $page_size = 6, $page_index = 0)
    {
        $task = $this->getTableName();
        $member = D("Member")->getTableName();
        $idea = D("Idea")->getTableName();
        $browse = D("TaskBrowse")->getTableName();
        $like = D("TaskLike")->getTableName();
        if ($where["$task.status"] == '' || empty($where["$task.status"])) {
            $where["$task.status"] = array(array('NEQ', 2), array('BETWEEN', '1,6'));
        }

        $field = "t_id,name,picture,$task.money,max_player_num,back_info,player,browse_num,brief,bid_start_time,bid_end_time,maxmoney,m_account,idea_n,browse_n,like_n,$task.utime,$task.status";
        $list = $this->field($field)
            ->where($where)
            ->join("LEFT JOIN $member ON $member.m_id=$task.m_id")//查询发表Idea的人的个人信息
            ->join("LEFT JOIN (
                SELECT COUNT(*)idea_n,MAX(money)maxmoney,`task_id` id FROM $idea WHERE $idea.status <> 9 GROUP BY `task_id`
                )I ON I.id=`t_id`")//查询发表idea的人数 idea_n
            ->join("LEFT JOIN (
                SELECT COUNT(*)browse_n,`task_id` id FROM $browse WHERE $browse.status <> 9 GROUP BY `task_id`
                )B ON B.id=`t_id`")//查询浏览过的人数 browse_n
            ->join("LEFT JOIN (
                SELECT COUNT(*)like_n,$like.`task_id` id FROM $like WHERE $like.status <> 9 GROUP BY `task_id`
                )L ON L.id=`t_id`")//查询点赞的人数
            ->order($order)->limit($page_index * $page_size, $page_size)->select();
        return $list;
    }

    /**
     * 查询多条数据,用于后台
     */
    public function selectTask2($where = array(), $order = '', $page_size = '', $parameter = array())
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
            $result = array('page' => $page_info, 'list' => $list, 'count' => $count);
        }
        return $result;
    }

    public function countTask($where,$m_id)
    {
        if ($m_id) $where['_string'] = "FIND_IN_SET('$m_id',player)";
        $res = $this->where($where)->count();
        return $res;
    }

    /**
     * 添加数据
     */
    public function addTask($data)
    {
        if (empty($data)) {
            return false;
        } else {
            if ($this->create($data)) {
                $result = $this->add();
                return $result;
            } else {
                return false;
            }
        }
    }

    /**
     * 添加浏览数
     */
    public function addBrowsenum($t_id)
    {
        $result = $this->execute("UPDATE toocms_task SET browse_num= browse_num+1 WHERE t_id = $t_id");
        return $result;
    }

    /**
     * 查询一条数据
     */
    public function findTask($where)
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

    public function getTaskCount($where)
    {
        if ($where['status'] == '' || empty($where['status'])) {
            $where['status'] = array('neq', '9');
        }
        $result = $this->where($where)->count();
        return $result[0];
    }

    /**
     * 编辑数据
     */
    public function editTask($where, $data)
    {
        if (empty($where) || empty($data)) {
            return false;
        }
        if ($this->create($data, 2)) {
            $result = $this->where($where)->save();
            return $result;
        }
        return false;
    }

    /**
     * 编辑数据
     */
    public function addPlayer($t_id, $u_id)
    {
        $res = $this->query("UPDATE {$this->getTableName()} SET player=CONCAT(player,'$u_id,') WHERE t_id='$t_id'");
        return $res;
    }

    /**
     * 删除数据
     */
    public function deleteTask($where)
    {
        if (empty($where)) {
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->data($data)->save();
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