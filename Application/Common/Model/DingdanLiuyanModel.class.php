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
 * Class DingdanLiuyanModel
 * @package Common\Model
 */
class DingdanLiuyanModel extends Model {
    protected $tableName = 'dingdan_liuyan';
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
    public function selectDingdanLiuyan($where = array(),$order = '',$page_size = 3,$page_index = 0){
        $liuyan = $this->getTableName();
        $mem_obj = D("Member");
        $member = $mem_obj->getTableName();

        if($where["$liuyan.status"] == ''|| empty($where["$liuyan.status"])){
            $where["$liuyan.status"] = array('neq',9);
        }

        $field = "$liuyan.liu_id,$liuyan.d_id,$liuyan.m_id,$liuyan.content,$liuyan.file,$liuyan.ctime,
        $member.m_account,$member.m_id,$member.m_email,$member.m_head";

        $list = $this->field($field)
            ->where($where)
            ->join("LEFT JOIN $member ON $member.m_id = $liuyan.m_id")
            ->order($order)->limit($page_index * $page_size,$page_size)->select();
        return $list;
    }

    /**
     * 添加数据
     */
    public function addDingdanLiuyan($data)
    {
        if (empty($data)) {
            return false;
        } else {
            $this->create($data);
            $result = $this->add();
            return $result;
        }
    }

    /**
     * 查询一条数据
     */
    public function findDingdanLiuyan($where){
        if(empty($where)){
            return false;
        }else{
            if($where["status"] == '' || empty($where["status"])){
                $where["status"] = array('neq','9');
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }
}