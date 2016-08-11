<?php
namespace Common\Model;
use Think\Model;
use Think\Controller;
/**
 * Class TaskModel
 * @package Common\Model
 */
class ServiceModel extends Model {
    protected $tableName = 'service';

    protected $_validate =array(
        array('subject','require','主题不能为空'),
        array('description','require','经验描述不能为空'),
        array('skills','require','具备技能不能为空'),
        array('price','require','服务身价不能为空'),
        array('type','require','服务类型不能为空'),
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
        public function selectService($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addService($data){
        if(empty($data)){
            return false;
        }else{
            $res = $this->create($data);//自动插入时间
            if($res){
                $result = $this->add();
            }
            return $result;
        }
    }

    /**
     * 查询一条数据
     */
    public function findService($where){
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
     * 修改作品
     */
    public function saveService($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        if($this->create($data,2)) {
            $result = $this->where($where)->save();
            return $result;
        }else
            return false;
    }

    /**
     * 删除作品
     */
    public function deleteService($where){
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