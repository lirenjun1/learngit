<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-11
 * Time: 上午10:14
 */
namespace Admin\Controller;
use Common\Model\DepositHistoryModel;
use Think\Controller;
class MemberController extends AdminBasicController{
    public $user = '';
    

    public function _initialize(){
        $this->user = D('user');
        
   
    }

    

    //用户列表
    public function memList(){
        /*if(!empty($_POST['con_mid'])){
            $where['login_id'] = array('LIKE',"%".$_POST['con_mid']."%");
        }*/
        if(!empty($_POST['con_maccount'])){
            $where['user_account'] = array('LIKE',"%".$_POST['con_maccount']."%");
        }
        if(!empty($_POST['con_memail'])){
            $where['login_email'] = array('LIKE',"%".$_POST['con_memail']."%");
        }

        $user = M('user');
        $where['status'] = array('neq','9');
        import("ORG.Util.Page"); // 导入分页类
        $count = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();

        $this->assign('page',$page_info);// 赋值分页输出
        // 查詢沒有被刪除的

        $data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('data',$data);

        $this->display('memList');
    }

    /*
     * 批量删除用户
     */
    public function deleteMemberAll(){
        $this->checkAuth('Member','deleteMemberAll');
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['m_id'] = $v;
            $res = $this->member->deleteMember($where);
            if($res){
                $result['success'] = "删除成功！";
            }else{
                $result['error'] = "删除失败，请稍后重试!";
            }
        }
        echo json_encode($result);
    }
    

    // 删除用户
    public function deleteMem()
    {

        $where['user_id'] = $_POST['login_id'];
    
        $data['status'] = 9;
        $user = M('user');
        $dele = $user->where($where)->save($data);
        
        if($dele){
            $result['success'] = "删除成功！";
        }else{
            $result['error'] = "删除失败，请稍后重试!";
        }
        echo json_encode($result);
    }
   
    
   

  
    //实名信息审核列表
    public function users()
    {
        if(!empty($_POST['user_account'])){
            $where['user_account'] = array('LIKE',"%".$_POST['user_account']."%");
        }
        if(!empty($_POST['user_name'])){
            $where['user_name'] = array('LIKE',"%".$_POST['user_name']."%");
        }
        // 实例化
        $user = M('user');
         $where['user_status'] = array('eq','1');
        import("ORG.Util.Page"); // 导入分页类
        $count = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->count(); //  查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();

        $this->assign('page',$page_info);// 赋值分页输出
        // 查询需要实名认证的用户
        $data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('data',$data);// 赋值分页输出
        
        $this->display('checkMem');
    }


    // 实名审核是否通过
    public function checkMemAction()
    {
        $where['user_id'] = $_GET['user_id'];        // 获取用户的ID
        $flag = $_GET['flag'];                       // 作为判断条件
        $user = M('user');                           // 实例化数据库

        if($flag == 1){
        // 执行通过
            $data['user_status'] = 2;      // 状态2为审核通过状态            
        }else{
        // 执行失败    
            $data['user_status'] = 3;      // 状态3位审核不通过状态
        }

        // 执行修改
        $data = $user->where($where)->save($data);
        if($data){
            $this->success('操作成功');    // 执行成功则返回操作成功
        }else{
            $this->error('操作失败');      // 失败返回操作失败
        }


    }



    // 用户的回收站
    public function userlist()
    {
        if(!empty($_POST['con_maccount'])){
            $where['user_account'] = array('LIKE',"%".$_POST['con_maccount']."%");
        }
        if(!empty($_POST['con_memail'])){
            $where['login_email'] = array('LIKE',"%".$_POST['con_memail']."%");
        }

        $user = M('user');
        $where['status'] = array('eq','9');
        import("ORG.Util.Page"); // 导入分页类

        $count = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();

        $this->assign('page',$page_info);// 赋值分页输出
        // 查詢沒有被刪除的

        $data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->limit($page->firstRow.','.$page->listRows)->select();


        $this->assign('data',$data);

        $this->display('memList');
    }




    // 用户恢复
    public function recovery()
    {
        // 恢复条件
        $where['user_id'] = $_POST['login_id'];
        
        // 状态为0   表示正常
        $data['status'] = 0;
        $user = M('user');
        $dele = $user->where($where)->save($data);
       
        // 判断是否执行成功
        if($dele){
            $result['success'] = "恢复成功";
        }else{
            $result['error'] = "恢复失败，请稍后重试!";
        }
        echo json_encode($result);
    }




    // 查看用户更多资料
    public function editmem()
    {
        // 实例化
        $user = M("user");

        // 获取传过来的ID
        $where['login_id']= $_GET['login_id'];

        // 联表查询用户
         $data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->select();

         $this->assign('da',$data['0']);

        
        $this->display('editmem');
    }


     /**
     * 分页  巴拉巴拉
     * 2016.8.11
     */
    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }
   
}