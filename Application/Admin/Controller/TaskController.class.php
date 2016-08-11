<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * Class TaskController
 * @package Admin\Controller
 * 项目类
 */
class TaskController extends AdminBasicController{
    
    public $member_obj = '';
   
    public function _initialize(){
       
        
    }

   

   
    /**
     *项目列表
     */
    public function taskList(){
       
        $this->display('taskList');
    }
    /**
     *修改任务
     */
    public function editTask(){
        $this->checkAuth('Task','editTask');
        if(empty($_POST)){
            $where['t_id']   = I('get.t_id');
            $where['status'] = array('neq',9);
            $task_info = $this->task_obj->findTask($where);
            $task_info['m_account'] = $this->member_obj->where(array('m_id'=>$task_info['m_id'],'status'=>array('neq',9)))->getField('m_account');
            $this->assign('task',$task_info);
           $this->display('editTask');
        }else{
            $where['t_id'] = I('post.t_id');
            $data = $this->task_obj->create();
            //接收头像
            //是否上传了LOGO
            if(!empty($_FILES['t_logo']['name'])){
                if (!is_dir('./Uploads/Task')) mkdir('./Uploads/Task', 0777);
                $res = uploadFile('','Task');
                if(empty($res['error'])){
                    $data['t_logo'] = $res['success'];
                }else{
                    $this->error($res['error']);
                }
            }else{
                $data['t_logo'] = I('post.t_logo1');
            }
            $data['t_brief'] = $_POST['t_brief'];
            $data['bid_start_time']    = strtotime($_POST['bid_start_time']);
            $data['bid_end_time']      = strtotime($_POST['bid_end_time']);
            $data['choose_start_time'] = strtotime($_POST['choose_start_time']);
            $data['choose_end_time']   = strtotime($_POST['choose_end_time']);
            if($data){
                $upd_res = $this->task_obj->editTask($where,$data);
                if($upd_res){
                    $this->success('编辑任务成功',U('taskList'));
                }else{
                    $this->error('编辑任务失败');
                }
            }else{
                $this->error($this->task_obj->getError());
            }
        }
    }
    
    /**
     * 项目详情
     */
    public function detailTask(){
        $where['status'] = array('neq',9);
        $where['t_id']   = $_GET['t_id'];
        $task_info = $this->task_obj->findTask($where);
        $task_info['light_spot'] = explode(',',$task_info['light_spot']);
        $task_info['aim_people'] = explode(',',$task_info['aim_people']);
        $task_info['aim'] = explode(',',$task_info['aim']);
        $task_info['key_word'] = explode(',',$task_info['key_word']);
        $task_info['other_require'] = explode(',',$task_info['other_require']);
        $this->assign('task',$task_info);
        $file = explode(',',$task_info['file']);
        foreach($file as $v){
            if($v){
                unset($f);
                $f['isImg'] = $this->isImage($v);
                $f["url"] = $v;
                $fileInfo[] = $f;
            }
        }

        $this->assign('file',$fileInfo);//文件

        //$this->assign('video',explode(',',substr($task_info['video'],0,-1)));//视频
        $this->assign('link',explode(',',substr($task_info['link'],0,-1)));//三方视频连接
        $reward = $this->reward_obj->selectReward($where,'','');
        $this->assign('reward',$reward);
        $this->display('detailTask');
    }

    

 

    
    

    /*******************************上传、删除文件  公共方法 开始   *******************************/
    /**
     *  上传文件
     *  传递参数的方式:POST
     *  需要传递的参数：
     *    文件存放的文件夹：folder
     *    文件名字在数据库中存放的字段：position
     *    上传文件为多图片：is
     *    默认的表为：task
     *  返回的参数：
     *    文件的名字
     */
    public function ajaxUploadFiles(){
        //下载文件夹
        $folder = $_POST['folder'];
        //数据库中的字段
        $position = $_POST['position'];
        if($folder == ''){
            echo "{ error: 上传路径不正确 }";//错误信息
            return;
        }
        $save_path = "./Uploads/".$folder."/".date('Ym')."/";
        $upInfo = getUpLoadFiles('',$save_path,'','',400,400,'',false,false);

        $pic = date('Ym')."/".$upInfo[0]['savename'];

        //保存到数据库
        $where['t_id']  = session('A_T_ID');
        //是否多图  1为多图
        if($_POST['isMulti'] == 1){
            //从数据库中改字段取出来
            $data[$position] = $this->task_obj->where($where)->getField($position);
            $data[$position] .= $pic.',';
        }else{
            $data[$position] = $pic;
        }
        $data['utime']  = time();
        $r = M('Task')->where($where)->data($data)->save();
        if($r) {
            //成功返回图片名字
            $arr = array('flag' => 0,'message'=>$pic);
        } else {
            $arr = array('flag' => 1,'message'=>'上传失败');
        }
        print json_encode($arr);
    }
    /**
     *  删除文件
     *  传递数据的方式：POST
     *  需要传递是数据：
     *  图片在数据库字段的位置：pid
     */
    public function delFile(){
        $where['t_id']  = session('A_T_ID');
        //要删除的文件在数据库中的字段
        $position = $_POST['position'];
        $logos = $this->task_obj->where($where)->getField($position);
        //删除前的数组
        $focus_arr_old = explode(',',substr($logos,0,-1));
        //删除后的字符串
        $focus_arr_new = '';
        for($i=0;$i<count($focus_arr_old);$i++){
            if($i!=$_POST['index'])
                $focus_arr_new .= $focus_arr_old[$i].',';
        }
        $data[$position] = $focus_arr_new;
        $r = M('Task')->where($where)->data($data)->save();
        if($r){
            $this->ajaxReturn(array('flag'=>0,'message'=>'删除成功'), Json);
        }else{
            $this->ajaxReturn(array('flag'=>1,'message'=>'删除失败'), Json);
        }

    }

    /*******************************上传、删除文件  公共方法   结束 ******************************/
    /**
     *  删除首页列表图片
     */
    public function delIndexPic(){
        $where['t_id']  = session('A_T_ID');
        //要删除的文件在数据库中的字段
        $position = $_POST['position'];
        $data[$position] = '';
        $r = M('Task')->where($where)->data($data)->save();
        if($r){
            $this->ajaxReturn(array('flag'=>0,'message'=>'删除成功'), Json);
        }else{
            $this->ajaxReturn(array('flag'=>1,'message'=>'删除失败'), Json);
        }
    }
    /**
     *  上传简介
     */
    public function addBrief(){
        $where['t_id'] = session('A_T_ID');
        $data['brief'] = $_POST['brief'];
        $data['utime'] = time();
        $edit_res = $this->task_obj->editTask($where,$data);
        if($edit_res){
            $this->ajaxReturn(array('flag'=>0,'message'=>'上传成功'));
        }else{
            $this->ajaxReturn(array('flag'=>1,'message'=>'上传失败'));
        }
    }
    /**
     *  上传视频
     */
    public function addVideo(){
        $where['t_id'] = session('A_T_ID');
        $where['status'] =array('neq',9);
        //找出原来的值
        $data = $this->task_obj->findTask($where);
        $data['link'] .= $_POST['link'].',';
        $data['video'] .= $_POST['video'].',';
        //$data = $this->bid->create();
        $data['utime'] = time();
        $edit_res = $this->task_obj->editTask($where,$data);
        if($edit_res){
            $this->ajaxReturn(array('flag'=>1,'message'=>'提交成功'));
        }else{
            $this->ajaxReturn(array('flag'=>0,'message'=>'提交失败'));
        }
    }
    /**
     *  删除视频
     *  传递数据的方式：POST
     *  需要传递是数据：
     *  图片在数据库字段的位置：pid
     */
    public function delVideo(){
        $where['t_id'] = session('A_T_ID');
        $result = $this->task_obj->findTask($where);
        //删除前的数组
        $video_arr_old = explode(',',substr($result['video'],0,-1));
        $link_arr_old = explode(',',substr($result['link'],0,-1));
        //删除后的字符串
        $video_arr_new = '';
        $link_arr_new = '';
        for($i=0;$i<count($video_arr_old);$i++){
            if($i!=$_POST['vid']){
                $video_arr_new .= $video_arr_old[$i].',';
                $link_arr_new .= $link_arr_old[$i].',';
            }
        }
        $data['video'] = $video_arr_new;
        $data['link'] = $link_arr_new;
        $r = M('Task')->where($where)->data($data)->save();
        if($r){
            $this->ajaxReturn(array('flag'=>0,'message'=>'删除成功'), Json);
        }else{
            $this->ajaxReturn(array('flag'=>1,'message'=>'删除失败'), Json);
        }
    }


    /**
     *  下载单个文件 公共函数
     * @param $file
     */
    public function downloads($file){
        $file_dir = $_SERVER['DOCUMENT_ROOT'].$file;
        //dump($file_dir);exit;
        $filename=pathinfo($file);
        if (!file_exists($file_dir)){
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit;
        } else {
            $file = fopen($file_dir,"r");
            header("Content-type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Accept-Length: ".filesize($file_dir));
            header("Content-Disposition: attachment; filename=".$filename['basename']);
            echo fread($file, filesize($file_dir));
            fclose($file);
        }
    }
    /**
     * 删除项目
     */
    public function  deleteTask(){
        $this->checkAuth('Task','deleteTask');
         $where['t_id'] = array('IN',I('request.t_id'));
         $data['utime'] = time();
         $data['status'] = 9;
         $upd_res = $this->task_obj->editTask($where,$data);
        if($upd_res){
            $this->success('删除任务成功');
        }else{
            $this->error('删除任务失败');
        }
    }
    /**
     * 编辑排序
     */
    public function editSort(){
        //修改条件 ID
        $where['t_id'] = I('post.id');
        $data['rank'] = I('post.sort');
        //修改操作
        $upd_res = $this->task_obj->editTask($where,$data);
        if($upd_res){
            $this->ajaxMsg('success','修改排序成功');
        }else{
            $this->ajaxMsg('error','修改排序失败，可能是未改变排序值');
        }
    }
}
