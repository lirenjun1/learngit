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
        $user = M('user');
        $where = "user_status != 9";
        import("ORG.Util.Page"); // 导入分页类
        $count = $user->where($where)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,2);
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出
        // 查詢沒有被刪除的
        $data = $user->join('leyou_login ON leyou_login.login_id = leyou_user.login_id')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
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
    

    
   
    
   
    //实名信息审核视图
    public function checkMem(){
        
        $this->display('checkMem');
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