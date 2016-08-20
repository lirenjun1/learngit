<?php
namespace Common\Model;
use Think\Model;
/**
 * Class DetaulsModel
 * @package Common\Model
 */
class ManufactorModel extends Model {
    protected $tableName = 'manufactor';
   
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );
    /**
     * @var manufactorse
     * 订单查询 更具条件
     */
    public function manufactorse($where)
    {
        $data = $this->where($where)->limit($page->firstRow.','.$page->listRows)->order('manufactor_id desc')->select();
        
        // 分页
        import("ORG.Util.Page"); // 导入分页类
        
        $count = $this->where($where)->limit($page->firstRow.','.$page->listRows)->order('manufactor_id desc')->count();
            
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        
        $page_info = $page->show();
        
        $result = array('page'=>$page_info,'data'=>$data);
        return $result;    
        
    }

   


    /**
     * 分页
     */
    private function setPageTheme(){
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }

}