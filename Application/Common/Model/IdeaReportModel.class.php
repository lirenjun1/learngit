<?php
/**
 * Created by PhpStorm.
 * User: theonead
 * Date: 10/13 0013
 * Time: 10:40
 */
namespace Common\Model;
use Think\Model;

/*
 * Class IdeaModel
 * @package Common\Model
 */
class IdeaReportModel extends Model{
    protected $tableName = 'task_idea_report';

    /**
     * @var array自动验证
     */
    protected $_validate =array(
        array('idea_id','require','数据请求错误！'),
        array('reason','require','举报理由不能为空！'),
    );

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
    public function selectReport($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addReport($data)
    {
        if (empty($data)) {
            return false;
        } else {
            if ($this->create($data)) {
                $res = $this->add();
                return $res;
            }
            return false;
        }
    }

    /**
     * 查询一条数据
     */
    public function findReport($where){
        if(empty($where)){
            return false;
        }else{
            if($where['status'] == '' || empty($where['status'])){
                $where['status'] = array('neq',9);
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }

    /**
     * 删除数据
     */
    public function deleteReport($where){
        if(empty($where)){
            return false;
        }
        $data['status'] = 9;
        $result = $this->where($where)->save($data);
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