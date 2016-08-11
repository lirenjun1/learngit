<?php
namespace Common\Model;
use Think\Model;
/**
 * Class DepositModel
 * @package Common\Model
 */
class DepositModel extends Model {
    protected $tableName = 'deposit';
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
    public function selectDeposit($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addDeposit($data){
        if(empty($data)){
            return false;
        }else if($this->create($data)){
            $result = $this->add();
            return $result;
        }
        return false;
    }

    /**
     * 查询一条数据
     */
    public function findDeposit($where){
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
    /**
     * 编辑数据
     */
    public function saveDeposit($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        if($this->create($data,2)){
            $result = $this->where($where)->save();
            return $result;
        }
        return false;
    }

    //金额增加
    public function DepositAdd($where,$add){
        $result = $this->where($where)->setInc('deposit',$add);
        if(!$result){
            $data = $where;
            $data['deposit'] = $add;
            $result = $this->addDeposit($data);
        }
        return $result;
    }

    //金额减少
    public function DepositDec($where,$dec){
        $result = $this->findDeposit($where);
        if($where && $result['deposit'] >= $dec){
            $result = $this->where($where)->setDec('deposit',$dec);
        }else{
            return false;
        }
        return $result;
    }

    /**
     * 删除数据
     */
    public function deleteDeposit($where){
        if(empty($where)){
            return false;
        }
        $result = $this->where($where)->delete();
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