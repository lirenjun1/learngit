<?php
namespace Common\Model;
use Think\Model;
/**
 * Class DetaulsModel
 * @package Common\Model
 */
class OrderModel extends Model {
    protected $tableName = 'order';
   
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );
    /**
     * @var addata
     * 订单查询 更具条件
     */
    public function addata($where,$desc)
    {
        // 查询状态为0的订单  
        // $where['order_status'] = array('eq',$where['order_status']['0']);

        $data = $this->join('lhl_user ON lhl_order.order_id = lhl_user.user_id')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->order($desc.' desc')->select();                    // 查询内容

        // 分页
        import("ORG.Util.Page"); // 导入分页类
        
        $count = M('order')->join('lhl_user ON lhl_order.order_id = lhl_user.user_id')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->count();                  // 获取数量
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        
        $page_info = $page->show();
        
        $result = array('page'=>$page_info,'data'=>$data,'count'=>$count);

        return $result;    
        
    }

    /**
     * @var query
     * 查询订单基本信息
     */
    public function query($where)
    {
        // 更具条件查询数据
        $result = $this->join('lhl_details ON lhl_order.order_id = lhl_details.details_id')->where($where)->select();
        
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