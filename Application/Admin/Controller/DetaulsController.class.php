<?php
/**
 * Date: 16-8-15
 * Time: 上午10:18
 */
namespace Admin\Controller;
use Common\Model\OrderModel;
use Think\Controller;
class DetaulsController extends AdminBasicController{
    public $order = '';
    public $details = '';
    

    public function _initialize(){
        $this->order = D('Common/order');
        
        
   
    }
	
	// 已下单
	public function already()
    {
        // 获取传过来的数据
        if(!empty($_POST['order_number'])){
            $where['order_number'] = array('LIKE',"%".$_POST['order_number']."%");
        }
        
        if(!empty($_POST['order_id'])){
            $where['order_id'] = array('LIKE',"%".$_POST['order_id']."%");

        }
        // 查询下单未付款的
       
        $where['order_status'] = array('0');

        $orde = $this->order->addata($where);
        
        

        var_dump($orde);
        // 渲染模版
        $this->display('already');
    }


     /**
     * 分页  巴拉巴拉
     * 2016.8.15
     */
    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }
   
}