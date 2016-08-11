<?php
namespace Common\Model;
use Think\Model;

/**
 * Class OnlineModel
 * @package Common\Model
 */
class OnlineModel extends Model
{
    protected $tableName = 'online';

    //查询一条数据
    public function findOnline($where){
        if(empty($where)){
            return false;
        }else{
            $result = $this->where($where)->find();
            return $result;
        }
    }
    //统计在线用户数
    public function countOnline(){
        //统计数据前删除失效数据(10分钟未操作)
        $now = time();
        $this->query("delete from toocms_online where update_time < $now - 60*10");
        //统计表中数据条数（广义上的在线数）
        $result = $this->query("SELECT COUNT(*) num from toocms_online");
        return $result[0]['num'];
    }
    //添加数据
    public function addOnline($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->add($data);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }
    //修改在线用户update_time
    public function modTime($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->save($data);
        return $result;
    }
}