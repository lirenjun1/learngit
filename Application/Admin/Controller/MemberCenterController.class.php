<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2015/10/15
 * Time: 11:30
 */
namespace Admin\Controller;
use Common\Model\DepositHistoryModel;
use Think\Controller;

class MemberCenterController extends AdminBasicController{

    

    public function _initialize(){
        
       
        parent::_initialize();
    }

    public function serviceInfo()
    {
        $this->userInfo();
        $where['m_id'] = $this->getUserInfo();
        $slist = $this->service_obj->selectService($where);
        $this->slist = $slist;
        $this->NAV_INDEX = 0;
        $this->display('serviceInfo');
    }
    public function prizeinfo(){
        $this->userInfo();
        $where['m_id'] = $this->getUserInfo();
        $plist = $this->prize_obj->selectPrize($where);
        if($plist){
            $this->plisttrue = true;
            $where['status'] = '1';
            $plist1 = $this->prize_obj->selectPrize($where);
            $where['status'] = '2';
            $plist2 = $this->prize_obj->selectPrize($where);
            $where['status'] = '3';
            $plist3 = $this->prize_obj->selectPrize($where);
            $this->plist1 = $plist1;
            $this->plist2 = $plist2;
            $this->plist3 = $plist3;
        }
        $this->NAV_INDEX = 9;
        $this->display("prizeinfo");
    }

    //打印需求页
    public function mybrief(){
        $this->userInfo();
        $m_id = $this->getUserInfo();
        $where['m_id'] = $m_id;
        $require_list = $this->require_obj->selectRequire($where,'',5);
        $require_list = $require_list['list'];
        $this->assign("require_list",$require_list);
        $this->NAV_INDEX = 8;
        $this->display('mybrief');
    }
    //当前登录用户信息
    public function userInfo(){
        $m_id = $this->getUserInfo();
        $where['m_id'] = $m_id;
        $mem = $this->mem_obj->findMember($where);
        $this->assign("mem",$mem);
    }

    /*
     * 基本信息保存操作
     */
    public function saveSelfInfo(){
        $m_id = $this->getUserInfo_ajax();

        $where['m_id'] = $m_id;
        $data['detail_address'] = $_POST['detail_address'];
        $data['school'] = $_POST['school'];
        $data['profession'] = $_POST['profession'];
        $data['home_page'] = $_POST['home_page'];
        $data['region_id'] = $_POST['region_id'];
        $data['common_skill'] = $_POST['common_skill'];
        $data['gender'] = $_POST['gender'];

        //避免刷分验证
        //验证是否加分:第一次补充信息加分
        $mem = $this->mem_obj->findMember($where);
        if(empty($mem['detail_address']) or empty($mem['school']) or
            empty($mem['profession']) or empty($mem['home_page']) or empty($mem['common_skill'])){
            $isJiafen = true;
        }else
            $isJiafen = false;
        if($this->mem_obj->saveMember($where,$data)) {
            if($isJiafen){
                //加积分
                if($this->addScore(1)){
                    $result['score_success'] = "积分 +1";
                }
            }
            $result['success'] = "基本信息保存成功";
        }else {
            $result['error'] = $this->mem_obj->getError();
        }
        echo json_encode($result);
    }


    
   

   
    /*
     *作品分享
     */
    public function mysharing(){
        $this->userInfo();
        $this->NAV_INDEX = 6;
        $this->display('mysharing3');
    }

    /*
     * 作品图片上传操作
     */
    public function uploadPhoto(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->subName   =  array('date', 'Ymd'); //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        $upload->savePath = '/info/'; // 设置附件上传（子）目录
        $upload->imgWidth = 500;
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $result['error'] = $upload->error;
        } else {// 上传成功
            $result['success'] = '上传成功！';
            $imgurl = $info['Filedata']['savepath'].$info['Filedata']['savename'];
            $result['imgurl'] = $imgurl;
        }
        echo json_encode($result);
    }

   

    /**
     * 缩放图片
     * @param $source原图片
     * @param $newfile新图片
     * @param $pre缩放比例
     */
    public function thumn($source,$pre,$newfile)
    {
        //获取图片尺寸
        list($s_w,$s_h)=getimagesize($source);
        //生成新的图片尺寸
        $new_w=$s_w*$pre;
        $new_h=$s_h*$pre;
        //创建新的图像
        $new_f=imagecreatetruecolor($new_w, $new_h);
        //用资源图片创建图像
        $sour_f=imagecreatefromjpeg($source);
        //拷贝资源图片到新图像
        imagecopyresampled($new_f, $sour_f, 0, 0, 0, 0, $new_w, $new_h, $s_w, $s_h);
        //输出图片到浏览器
        imagejpeg($new_f,$newfile);
        imagedestroy($new_f);
        imagedestroy($sour_f);
    }

}