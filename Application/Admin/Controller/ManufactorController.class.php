<?php
/**
 * 订单管理操作
 * Date: 16-8-15
 * Time: 上午10:18
 */
namespace Admin\Controller;
use Common\Model\ManufactorModel;
use Think\Controller;
class ManufactorController extends AdminBasicController{
    public $manufactor = '';
    

    public function _initialize(){
        $this->manufactor = D('Common/manufactor');
        
        parent::_initialize();
   
    }
	
	// 所有商家
	public function merchant()
    {
        $management = "厂家列表";
        $this->assign('order',$management);    // 标题输出

        // 获取传过来的数据
        if(!empty($_POST['manufactor_name'])){
            $where['manufactor_name'] = array('LIKE',"%".$_POST['manufactor_name']."%");
        }
        
        if(!empty($_POST['manufactor_id'])){
            $where['manufactor_id'] = array('LIKE',"%".$_POST['manufactor_id']."%");

        }
        if(!empty($_POST['manufactor_account'])){
            $where['manufactor_account'] = array('LIKE',"%".$_POST['manufactor_account']."%");

        }                
        // 执行查询
        $merchan = $this->manufactor->manufactorse($where);
    
        $this->assign('page',$merchan['page']);    // 赋值分页输出
        $this->assign('data',$merchan['data']);    // 赋值输出
        // 渲染模版
        $this->display('merchant');
    }


    // 厂家修改 视图
    public function edit()
    {
        $where['manufactor_id'] = $_GET['manufactor_id'];
        $edit = M('manufactor')->where($where)->select();

        $this->assign('da',$edit[0]);    // 赋值输出
        $this->display('edit');
    } 


    // 执行修改
    public function addMem()
    {
        $data['manufactor_name'] = $_POST['manufactor_name'];
        $data['manufactor_logo'] = $_POST['imgurl'];
        $data['manufactor_contact'] = $_POST['manufactor_contact'];
        $data['manufactor_address'] = $_POST['manufactor_address'];


        $where['manufactor_id'] = $_POST['manufactor_id'];
        $manufactor = M('manufactor');

        $manu = $manufactor->where($where)->save($data);

        if($manu){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('操作成功，正在跳转。。。');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败!请稍后重试');
        }
         
    }

    
    // 厂家添加视图
    public function addmerc()
    {


        $this->display('addmerc');
    }


    public function addmanufactor()
    {
       $data['manufactor_name'] = $_POST['manufactor_name'];
       $data['manufactor_logo'] = $_POST['imgurl'];
       $data['manufactor_account'] = $_POST['manufactor_account'];
       $data['manufactor_contact'] = $_POST['manufactor_contact'];
       $data['manufactor_address'] = $_POST['manufactor_address'];
       $data['manufactor_pwd'] = md5($_POST['manufactor_pwd']);
       $data['manufactor_time'] = time();
       // 实例化
       $manufactor = M('manufactor');
       $da = $manufactor->data($data)->add();

       if($da){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('操作成功，正在跳转。。。');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败!请稍后重试');
        }
    }


    // 查询厂家名是否已经存在
    public function query()
    {
        // 实例化表
        $manufactor = M('manufactor');

        $where['manufactor_name'] = $_POST['manufactor_name'];

        $manu = $manufactor->field('manufactor_name')->where($where)->select();
        
        if(empty($manu)){
            $result['success'] = "可用";
        }else{
            $result['error'] = "已存在,请修改";
        }

           
        echo json_encode($result); 
    }


    // 查询厂家登录账户是否存在
    public function account()
    {
        // 实例化表
        $manufactor = M('manufactor');

        $where['manufactor_account'] = $_POST['manufactor_account'];

        $manu = $manufactor->field('manufactor_account')->where($where)->select();
        
        if(empty($manu)){
            $result['success'] = "可用";
        }else{
            $result['error'] = "已存在,请修改";
        }

           
        echo json_encode($result); 
    }


    // 禁止
    public function prohibit()
    {
        $where['manufactor_id'] = $_POST['manufactor_id'];
    
        $data['manufactor_status'] = 9;
        $manufactor = M('manufactor');
        $dele = $manufactor->where($where)->save($data);
        
        if($dele){
            $result['success'] = "操作成功！";
        }else{
            $result['error'] = "操作失败!请稍后重试";
        }
        echo json_encode($result); 
    }



    // 恢复商家
    public function recovery()
    {
        $where['manufactor_id'] = $_POST['manufactor_id'];
    
        $data['manufactor_status'] = 0;
        $manufactor = M('manufactor');
        $dele = $manufactor->where($where)->save($data);
        
        if($dele){
            $result['success'] = "恢复成功！";
        }else{
            $result['error'] = "恢复失败!请稍后重试";
        }
        echo json_encode($result); 
    }


    // 取消为付款的订单
    public function cancel()
    {
        $where['order_id'] = $_POST['order_id'];
    
        $data['order_status'] = 9;
        $order = M('order');
        $dele = $order->where($where)->save($data);
        
        if($dele){
            $result['success'] = "订单取消成功！";
        }else{
            $result['error'] = "订单取消失败!请稍后重试";
        }
        echo json_encode($result); 
    }


}