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
class DingdanMsgModel extends Model {
    protected $tableName = 'dingdan_msg';
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
    public function addDingdanMsg($data)
    {
        if (empty($data)) {
            return false;
        } else {
            $this->create($data);
            $result = $this->add();
            return $result;
        }
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