<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2015/10/29
 * Time: 15:11
 */

namespace Common\Model;
use Think\Model;


class WorkBrowseModel extends Model{
    protected $tableName = 'work_browse';

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'),
    );

    /**
     * 查询多条数据
     */
    public function selectBrowse($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addBrowse($m_id,$work_id){
        if(!$m_id || !$work_id) return false;

        $where['m_id'] = $m_id;
        $where['work_id'] = $work_id;

        $count = $this->where($where)->count();
        if(!$count){
            $where['ctime'] = time();
            $where['utime'] = time();
            $this->data($where)->add();
            return true;
        }
        return false;
    }
    /**
     * 查询一条数据
     */
    public function findBrowse($where){
        if(empty($where)) return false;

        if($where['status'] == '' || empty($where['status'])){
            $where['status'] = array('neq','9');
        }
        $result = $this->where($where)->find();
        return $result;
    }

    /**
     * 分页样式
     */
    private function setPageTheme(){
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }

    public function getBrowseCount($where){
        if($where['status'] == ''|| empty($where['status'])){
            $where['status'] = array('neq',9);
        }
        return $this->where($where)->count();
    }

}