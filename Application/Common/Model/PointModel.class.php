<?php
/**
 * Created by PhpStorm.
 * User: wanglei
 * Date: 7/16/15
 * Time: 10:26 PM
 */

namespace Common\Model;


use Think\Model;

class PointModel extends Model {
    protected $tableName = 'point';

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对time字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对time字段在插入的时候写入当前时间戳
    );

    public function generateInvitation() {
        //随机生成
        $number=rand(10000,99999);
        return $number;
    }
    /**
     * 查询多条数据
     */
    public function selectInvitation($where = array(),$order = '',$page_size = '',$parameter = array()){
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

    public function usedCount() {
        $where['status'] = 9;
        return $this->where($where)->count();
    }
    /**
     * 添加数据
     */
    public function addInvitation($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
    /**
     * 查询一条数据
     */
    public function findInvitation($where){
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
    public function editInvitation($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteInvitation($where){
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