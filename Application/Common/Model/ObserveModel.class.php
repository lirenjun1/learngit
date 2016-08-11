<?php
namespace Common\Model;

use Think\Model;

/**
 * Class ObserveModel
 * @package Common\Model
 */
class ObserveModel extends Model
{
    protected $tableName = 'member_observe';

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array(
        array('ctime', 'time', self::MODEL_INSERT, 'function'), // 对ctime字段在插入的时候写入当前时间戳
    );

    /**
     * 查询多条数据
     */
    public function selectObserve($where = array(), $order = '', $page_size = '', $parameter = array())
    {
        if ($where['status'] == '' || empty($where['status'])) {
            $where['status'] = array('neq', 9);
        }
        if ($page_size == '') {
            $result = $this->where($where)->order($order)->select();
        } else {
            $count = $this->where($where)->count();
            $page = new \Think\Page($count, $page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme', $this->setPageTheme());
            $page_info = $page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow, $page_size)
                ->select();
            $result = array('page' => $page_info, 'list' => $list);
        }
        return $result;
    }

    /**
     * 添加数据
     */
    public function addObserve($data)
    {
        //不允许收藏自己
        if (empty($data) || $data['observer_id'] == $data['observed_id']) {
            return false;
        } else {
            $data['ctime'] = time();
            $data['utime'] = time();
            $result = $this->data($data)->add();
            return $result;
        }
    }

    /**
     * 查询一条数据
     */
    public function findObserve($where)
    {
        if (empty($where)) {
            return false;
        } else {
            if ($where['status'] == '' || empty($where['status'])) {
                $where['status'] = array('neq', '9');
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }

    /**
     * 编辑数据
     */
    public function editObserve($where, $data)
    {
        if (empty($where) || empty($data)) {
            return false;
        }
        $data['utime'] = time();
        $result = $this->where($where)->data($data)->save();
        return $result;
    }

    /**
     * 删除数据
     */
    public function deleteObserve($where)
    {
        if (empty($where)) {
            return false;
        }
        $result = $this->where($where)->delete();
        return $result;
    }

    /**
     * 分页样式
     */
    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }
}