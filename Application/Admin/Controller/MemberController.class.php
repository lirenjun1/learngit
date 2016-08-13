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
        $count = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->count(); // 查询满足要求的总记录数
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

        $login_id = $_POST['login_id'];
        
        $data['status'] = 9;
        $user = M('user');
        $where['login_id'] = $login_id;
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
        if(!empty($_POST['con_maccount'])){
            $where['user_account'] = array('LIKE',"%".$_POST['con_maccount']."%");
        }
        if(!empty($_POST['con_memail'])){
            $where['login_email'] = array('LIKE',"%".$_POST['con_memail']."%");
        }






        $this->display('checkMem');
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
        $count = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,1);
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

        $login_id = $_POST['login_id'];
        
        $data['status'] = 0;
        $user = M('user');
        $where['login_id'] = $login_id;
        $dele = $user->where($where)->save($data);
       

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
        //$data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.login_id')->where($where)->select();
         $data = $user->join('lhl_login ON lhl_login.login_id = lhl_user.user_id')->where($where)->select();

         $this->assign('da',$data['0']);

        var_dump($data);
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