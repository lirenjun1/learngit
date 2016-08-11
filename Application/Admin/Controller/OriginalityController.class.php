<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2016/5/28
 * Time: 13:58
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
use Think\Model;

class OriginalityController extends AdminBasicController
{
    public $apply_obj = '';
    public $email_obj = '';
    public function  _initialize()
    {
        $this->apply_obj = D('apply');
        $this->email_obj = D('Email');
        parent::_initialize();
    }

    // ´´ÒâÉêÇëÊÓÍ¼
    public function apply()
    {
        $apply = M('apply');
        import("ORG.Util.Page"); // 导入分页类
        $count = $apply->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出
        $apply_obj  = $apply->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('apply_obj',$apply_obj);// 赋值数据集
        // 渲染
        $this->display('apply');

    }


    // ´´ÒâÈËÉêÇëÏêÇé
    public function detaapply()
    {
        $id = I("id");
        $apply = $this->apply_obj->where("id=".$id)->select();

        $this->assign('apply',$apply);

        $this->display('detaapply');
    }

    /**
     * É¾³ýÏîÄ¿
     */
    public function  deleteapply(){
        $where['id']=array('in',$id);
        $upd_res = $this->apply_obj->where($where)->delete();

        if($upd_res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 待审核状态中的创意人
     *
     */
    public function originality()
    {
        if(!empty($_POST['people_id'])){
            $where['people_id'] = array('LIKE',"%".$_POST['people_id']."%");
        }
        if(!empty($_POST['mem_id'])){
            $where['mem_id'] = array('LIKE',"%".$_POST['mem_id']."%");
        }
        $originality = M('originality');
        // 待审核状态为0
        $where['auditpass'] = 0;
        import("ORG.Util.Page"); // 导入分页类
        $count = $originality->where($where)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出
        $originality_list  = $originality->where($where)->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('originality_list',$originality_list);// 赋值数据集
        // 渲染
        $this->display('originality');

        /*$originality_list = M('originality')->select();
        $this->assign('originality_list',$originality_list);
        $this->display();*/
    }

    /**
     * 通过的创意人
     * 2016.7.1   doudou
     */
    public function originalit()
    {
        // 创意人ID搜索
        if(!empty($_POST['people_id'])){
            $where['people_id'] = array('LIKE',"%".$_POST['people_id']."%");
        }
        // 用户ID搜索
        if(!empty($_POST['mem_id'])){
            $where['mem_id'] = array('LIKE',"%".$_POST['mem_id']."%");
        }
        $originality = M('originality');
        // 审核通过状态为1
        $where['auditpass'] = 1;
        import("ORG.Util.Page"); // 导入分页类
        $count = $originality->where($where)->count(); // 查询满足要求的总记录数

        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出
        $originality_list  = $originality->where($where)->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('originality_list',$originality_list);// 赋值数据集
        // 渲染
        $this->display('originalit');

        /*$originality_list = M('originality')->select();
        $this->assign('originality_list',$originality_list);
        $this->display();*/
    }


    public function detaoriginality(){
        $id = intval(I('id'));//intval()强制一下
        if($id){//判断有没有id
            $orig = M('originality')->where(array('people_id'=>$id))->select();
            $works = $orig['0']['people_works'];
            $strarr = explode(",",$works);
            $this->assign('strarr',$strarr);
            $this->assign('orig',$orig);
        }
        $this->display();
    }
    public function del_originality(){
        $id = intval(I('id'));
        if($id){
            // $list = M('originality')->where(array('people_id'=>$id))->delete();
            $data['people_id'] = $id;
     
            $list = M('originality')->where($data)->delete();
            if($list){
                $this->success('删除成功');
            }else{
              $this->error('删除失败');  
            }
        }
    }
    public function del_or(){
        $id = intval(I('id'));
        if($id){
            // $list = M('originality')->where(array('people_id'=>$id))->delete();
            $data['id'] = $id;
     
            $list = M('apply')->where($data)->delete();
            if($list){
                $this->success('删除成功');
            }else{
              $this->error('删除失败');  
            }
        }
    }
    public function deleteOriginalityAll(){
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['people_id'] = $v;
            $res = M('originality')->where($where)->delete();
            if($res){
                $result['success'] = "删除成功";
            }else{
                $result['error'] = "删除失败!";
            }
        }
        echo json_encode($result);
    }

     public function deleteApplyAll(){
        $id = explode(',',$_POST['id']);
        foreach($id as $v){
            $where['id'] = $v;
            dump($v);
            $res = M('apply')->where($where)->delete();
            if($res){
                $result['success'] = "删除成功";
            }else{
                $result['error'] = "删除失败!";
            }
        }
        echo json_encode($result);
    }

    /**
     * 审核通过
     * 2016.6.16
     */
    public function pass()
    {

        $body = '<div id="qm_con_body"><div id="mailContentContainer" class="qmbox qm_con_body_content qqmail_webmail_only"><div id="qm_con_body"><div id="mailContentContainer" class="qmbox qm_con_body_content qqmail_webmail_only">
                    <div style="min-width: 600px;background-color:#2A2A2A;padding:80px 0 75px;letter-spacing:1.5px" align="center">
                        <div style="width:265px;color:#fff;font:16px/20px 微软雅黑;">
                           恭喜你！成功通过CIA审核！
                        </div>
                        <div style="width:500px;border-radius:5px;background:#fde032;padding:50px 30px 50px;margin-top:50px;font-size: 14px;">
                            从现在起，你就是我们的创意特工了！<br><br>随时准备接受私密任务的挑战吧！<br>
                            <a href="http://www.pitchina.com.cn" style="border-radius:5px;padding:0 30px;background:#fff;display:inline-block;margin-top:80px;font:600 16px/45px 微软雅黑;color:#444; text-decoration:none;" target="_blank">进入大创意</a>

                        </div>
                       <div style="width:385px;font:italic 13px/24px \'proxima_nova_rgregular\', Helvetica;color:#fff;margin-top:30px" align="left">
                            Copyright©2015
                            <a href="http://www.pitchina.com.cn/index.php" style="color:#fde032;margin-left: 5px;" target="_blank">Pitchina.com.cn</a>
                            请勿回复此邮件
                        </div>
                    </div>
                    <style type="text/css">.qmbox .qmbox style,.qmbox .qmbox script,.qmbox .qmbox head,.qmbox .qmbox link,.qmbox .qmbox meta{display: none !important;}
                    </style></div></div>
                    <style type="text/css">.qmbox style, .qmbox script, .qmbox head, .qmbox link, .qmbox meta {display: none !important;}</style></div></div>';
        // 获取用户的ID
        $m_id = I('mem_id');
        // 实例化member表
        $member = M('member');
        $memb = $member->where("m_id=".$m_id)->field('m_email')->select();
        $email = $memb['0']['m_email'];
        // 用创意人ID作为条件
        $data['auditpass'] = 1;
        $data['people_name'] = $_POST['people_name'];
        $data['people_sex'] = $_POST['people_sex'];
        $data['people_age'] = $_POST['people_age'];
        $data['people_nicheng'] = $_POST['people_nicheng'];
        $data['people_once'] = $_POST['people_once'];
        $data['people_onces'] = $_POST['people_onces'];
        $data['people_now'] = $_POST['people_now'];
        $data['people_brand'] = $_POST['people_brand'];
        $data['people_category'] = $_POST['people_category'];
        $data['people_service'] = $_POST['people_service'];
        $data['people_personal'] = $_POST['people_personal'];
        // 实例化
        $apply = M('originality');
        // 判断代号是不是存在 如果存在则不通过 不存在则通过
        $wher['people_nicheng'] = $data['people_nicheng'];
        $wher['auditpass'] = 1;
        $people_nicheng = $apply->where($wher)->select();
        if($people_nicheng){
            $result['error'] = "代号重复,请做修改";
        }else{
            $where['mem_id'] = $m_id;
            $app = $apply->where($where)->save($data);
            if($app){
                $this->email_obj->sendEmail($email,'','恭喜你！成功通过CIA审核！',$body);

                $result['success'] = "审核成功";
            }else{
                $result['error'] = "数据异常";
            }
        }

        echo json_encode($result);
    }

    /**
     * 创意人资料修改
     * 2016.7.5   豆豆
     */
    public function modify()
    {
        $m_id = I('mem_id');
        $where['mem_id'] = $m_id;
        $data['people_name'] = $_POST['people_name'];
        $data['people_sex'] = $_POST['people_sex'];
        $data['people_age'] = $_POST['people_age'];
        $data['people_nicheng'] = $_POST['people_nicheng'];
        $data['people_once'] = $_POST['people_once'];
        $data['people_onces'] = $_POST['people_onces'];
        $data['people_now'] = $_POST['people_now'];
        $data['people_brand'] = $_POST['people_brand'];
        $data['people_category'] = $_POST['people_category'];
        $data['people_service'] = $_POST['people_service'];
        $data['people_personal'] = $_POST['people_personal'];
        // 实例化
        $apply = M('originality');
        $app = $apply->where($where)->save($data);
        if($app){
            // $this->email_obj->sendEmail($email,'','恭喜你！成功通过CIA审核！',$body);

            $result['success'] = "修改成功";
        }else{
            $result['error'] = "数据异常";
        }
        echo json_encode($result);

    }




    /**
     * 分页  巴拉巴拉
     * 2016.6.14
     */
    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }


}