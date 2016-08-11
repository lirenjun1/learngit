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
 * Class DingdanMsgModel
 * @package Common\Model
 */
class TaskMsgModel extends Model {
    protected $tableName = 'task_msg';
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
    );

    /**
     * 添加数据
     */
    public function addTaskMsg($taskid,$type,$uid)
    {
        $data['t_id'] = $taskid;
        $data['type'] = $type;
        switch ($type) {
            case '收到脑洞':
                $time = time();
                $res = $this->execute("UPDATE __TABLE__ SET ctime = $time,tag = tag + 1
                                    WHERE t_id = $taskid  AND type = '收到脑洞' AND msg_status = '未读'");
                if ($res) return true;
                $tobj = D('Task');
                $task = $tobj->field("m_id")->findTask(array('t_id' => $taskid));
                if ($task) {
                    $data['m_id'] = $task['m_id'];
                    $data['tag'] = 1;
                } else {
                    return false;
                }
                break;
            case '':
                break;
            default:
                $data['m_id'] = $uid;
                break;
        }
        if ($this->create($data)) {
            $result = $this->add();
        }
        return $result;
    }


    /*
 * 将阅读状态设置为1
 */
    public function setIsRead($id)
    {
        $where['msg_id'] = $id;
        $where['to_mid'] = session('M_ID');
        $data['msg_status'] = '已读';
        $res = $this->where($where)->save($data);
        return !!$res;
    }


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
    public function findMsg($where){
        if(empty($where)){
            return false;
        }else{
            if($where['msg_status'] == '' || empty($where['msg_status'])){
                $where['msg_status'] = array('neq','删除');
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }
}