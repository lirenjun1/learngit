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
 * Class DingdanDongtaiModel
 * @package Common\Model
 */
class DingdanDongtaiModel extends Model {
    protected $tableName = 'dingdan_dongtai';
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
    public function selectDingdanDongtai($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addDingdanDongtai($data)
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
    public function findDingdanDongtai($where){
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
}