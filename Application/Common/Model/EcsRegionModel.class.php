<?php
namespace Common\Model;
use Think\Model;
/**
 * Class RegionModel
 * @package Common\Model
 */
class EcsRegionModel extends Model {
    protected $tableName = 'ecs_region';
    /**
     * @var array
     * 自动验证   使用create()方法时自动调用
     */
    protected $_validate = array(
        array('region_name','require','地区名称不能为空！'), //空验证  默认情况下用正则进行验证
    );

    /**
     * 查询多条数据
     */
        public function selectRegion($where = array(),$order = '',$page_size = '',$parameter = array()){
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
    public function addRegion($data){
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
    public function findRegion($where){
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
     * 查询完全的地址
     * @param $region_id 最小的地区id
     */
    public function findFullRegion($region_id){
        do{
            unset($where);
            $where['region_id']  = $region_id;
            $regin = $this->findRegion($where);
            if(!$regin) break;
            $Regin["$region_id"] = $regin['region_name'];
            $region_id = $regin['parent_id'];
        }while($region_id);
        if($Regin) {
            return $Regin;
        }
        else{
            return array("保密");
        }
    }

    /**
     * 编辑数据
     */
    public function editRegion($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->data($data)->save();
        return $result;
    }
    /**
     * 删除数据
     */
    public function deleteRegion($where){
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

    /**
     * 数据相关处理
     * 当许多地方需要将取出的原数据惊醒改变格式 或添加某些相关数据
     */
    public function manageAdminInfo(){

    }
}