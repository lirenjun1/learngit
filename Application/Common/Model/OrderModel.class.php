<?php
namespace Common\Model;
use Think\Model;
/**
 * Class DetaulsModel
 * @package Common\Model
 */
class OrderModel extends Model {
    protected $tableName = 'order';
   
    
   
    public function addata($where)
    {
        // 查询状态为0的订单  
        $where['order_status'] = $where['order_status']['0'];
        
        
        $data = $this->join('lhl_user ON lhl_order.order_id = lhl_user.user_id')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->select(); 
        
        import("ORG.Util.Page"); // 导入分页类
        $page = new \Think\Page($count,10);

        $count = $this->join('lhl_user ON lhl_order.order_id = lhl_user.user_id')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->count(); 
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        var_dump($page_info);
        $result = array('page'=>$page_info,'data'=>$data,'count'=>$count);

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