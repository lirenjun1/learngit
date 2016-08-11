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
 * Class DingdanModel
 * @package Common\Model
 */
class DingdanModel extends Model {
    protected $tableName = 'Dingdan';

    protected $_validate = array(
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
    public function selectDingdan($where = array(),$order = '',$page_size = '',$parameter = array()){
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
     * 添加数据
     */
    public function addDingdan($data){
        if(empty($data)){
            return false;
        }else{
            $this->create($data);
            $result = $this->add();
            return $result;
        }
    }
    /**
     * 查询一条数据
     */
    public function findDingdan($where){
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
    /*
    * 更新数据
    */
    public function saveDingdan($where=array(),$data){
        if(empty($data)){
            return false;
        }else{
            if($this->create($data,2)) {
                $result = $this->where($where)->save();
                return $result;
            }
            return false;
        }
    }
    /**
     * 删除数据
     */
    public function deleteDingdan($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }

    //接单率
    public function jiedanlv($where){
        if(empty($where)){
            return false;
        }
        //订单数
        $result1 = $this->where($where)->count();
        //仅获取已接单
        $where['status'] = array("neq",'2');
        $result2 = $this->where($where)->count();
        $result = $result2/$result1;
        return $result*100;
    }

    //守时率
    public function shoushilv($where){
        if(empty($where)){
            return false;
        }
        //订单数
        $result1 = $this->where($where)->count();
        //仅获取未完成
        $where['status'] = array("eq",'4');
        $result2 = $this->where($where)->count();
        $result = $result2/$result1;
        return 100-$result*100;
    }

    //好评率
    public function haopinglv($where){
        if(empty($where)){
            return false;
        }
        //订单数
        $result1 = $this->where($where)->count();
        //仅获取好评单
        $where['remark'] = array("eq",'好评');
        $result2 = $this->where($where)->count();
        $result = $result2/$result1;
        return $result*100;
    }

    //获取成交额
    public function sumMoney($where){
        if(empty($where)){
            return false;
        }
        //仅获取已完成订单
        $where['status'] = 5;
        $result = $this->where($where)->sum('is_money');
        return $result;
    }
    //获取项目数
    public function countDingdan($where){
        if(empty($where)){
            return false;
        }
        //仅获取已完成订单
        $where['status'] = 5;
        $result = $this->where($where)->count();
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

}