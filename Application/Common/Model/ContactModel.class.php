<?php
namespace Common\Model;
use Think\Model;

class ContactModel extends Model {
    protected $tableName = 'contact';
    /**
     * @var array
     * 自动验证   使用create()方法时自动调用
     */
    protected $_validate = array(
        array('username','require','姓名不能为空！'), //空验证  默认情况下用正则进行验证
        array('userphone','require','电话号不能为空！'), //空验证  默认情况下用正则进行验证
        array('useremail','require','邮箱不能为空！'), //空验证  默认情况下用正则进行验证
        array('userinfo','require','详细信息不能为空！'), //空验证  默认情况下用正则进行验证
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
    public function selectContact($where = array(),$order = '',$page_size = '',$parameter = array()){
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
            $result = array('page'=>$page_info,'list'=>$list);
        }
        return $result;
    }
    /**
     * 添加数据
     */
    public function addContact($data){
        if(empty($data)){
            return false;
        }else{
            if($this->create($data)){
                $result = $this->add();
                return $result;
            }else{
                return false;
            }
        }
    }

    /**
     *   删除消息
     */
    public function deleteContact($where){
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