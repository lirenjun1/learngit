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
	
	// 全部订单
	public function already()
    {
        $management = "全部订单";
        $this->assign('order',$management);    // 标题输出

        // 获取传过来的数据
        if(!empty($_POST['order_number'])){
            $where['order_number'] = array('LIKE',"%".$_POST['order_number']."%");
        }
        
        if(!empty($_POST['order_id'])){
            $where['order_id'] = array('LIKE',"%".$_POST['order_id']."%");

        }
        if(!empty($_POST['login_id'])){
            $where['login_id'] = array('LIKE',"%".$_POST['login_id']."%");

        }
        // 已下单待处理    状态为0表示已经下单  但是没有付款
        // $where['order_status'] = array(0);
        
        $orde = $this->order->addata($where);   // addata  是查询操作
    
        $this->assign('page',$orde['page']);    // 赋值分页输出
        $this->assign('data',$orde['data']);    // 赋值输出
        // 渲染模版
        $this->display('already');
    }


    // 待发货
    public function backorders()
    {
        $management = "待发货";
        $this->assign('order',$management);    // 标题输出

        $where['order_status'] = array(1);       // 订单状态   1表示待发货状态

        $orde = $this->order->addata($where);       // addata  是查询操作
    
        $this->assign('page',$orde['page']);    // 赋值分页输出
        $this->assign('data',$orde['data']);    // 赋值输出



        $this->display('already');
    }

    // 已发货
    public function delivered(){
        $management = "已发货";
        $this->assign('order',$management);    // 标题输出

        $where['order_status'] = array(2);       // 订单状态   2表示已发货状态

        $orde = $this->order->addata($where);       // addata  是查询操作
    
        $this->assign('page',$orde['page']);    // 赋值分页输出
        $this->assign('data',$orde['data']);    // 赋值输出



        $this->display('already');
    }

    // 已发货
    public function dealgoes(){
        $management = "交易完成";
        $this->assign('order',$management);    // 标题输出

        $where['order_status'] = array(3);       // 订单状态   3表示已发货状态

        $orde = $this->order->addata($where);       // addata  是查询操作
    
        $this->assign('page',$orde['page']);    // 赋值分页输出
        $this->assign('data',$orde['data']);    // 赋值输出



        $this->display('already');
    }
    
   
}