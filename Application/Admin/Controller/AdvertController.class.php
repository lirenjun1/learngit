<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 焦点图控制类
 * 薛晓峰
 * 2014-12-19
 */
class AdvertController extends AdminBasicController {

    public $ad_obj = '';
    public function _initialize(){
        $this->checkLogin();
        $this->ad_obj = D('Advert');
    }

    /**
     * 焦点图列表
     * 2014-12-19
     */
    public function advertList(){
        //导航列表
        if(!empty($_REQUEST['ad_desc'])){   //焦点图描述查询
            $where['ad_desc'] = array('LIKE',"%".I('request.ad_desc')."%");
            $parameter['ad_desc'] = $_REQUEST['ad_desc'];
        }
        if(!empty($_REQUEST['ad_position'])){   //焦点图类型查询
            $where['ad_position'] = I('request.ad_position');
            $parameter['ad_position'] = $_REQUEST['ad_position'];
        }
        $ad_list = $this->ad_obj->selectAdvert($where,'sort_order desc',$this->getPageNumber(),$parameter);
        //$arr = C('AD_POSITION');
        if($ad_list['list']){
            $this->assign('ad_list',$ad_list['list']);
            $this->assign('page',$ad_list['page']);
        }
        //编辑后返回
        $this->setEditBack(U('Advert/advertList',$_REQUEST));
        //焦点图位置
        //提交的参数传回前台 赋值到搜索表单
        $this->assign('request',$_REQUEST);
        $this->display('advertList');
    }

    /**
     * 添加焦点图
     * 2014-12-19
     */
    public function addAdvert(){
        $this->checkAuth('Advert','addAdvert');
        if(empty($_POST)){
            //焦点图位置
            $this->display('addAdvert');
        }else{
            $data = $_POST;
            if($data){
                //是否上传了焦点图图片
                if($data['ad_pic_url'] == ''){
                    $this->error('添加失败，请上传焦点图。');
                }
                $add_res = $this->ad_obj->addAdvert($data);
                if($add_res){
                    $this->success('添加成功',U('Advert/advertList'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error($this->ad_obj->getError());
            }
        }
    }

    //焦点图图片上传
    public function uploadAdvertLogo(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     ''; // 设置附件上传根目录
        $upload->subName   =  array('date', 'Ymd'); //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        $upload->savePath = '/Advert/'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $result['error'] = '上传失败，请选择正确的图片类型（jpg,png,jpeg）！';
        } else {// 上传成功
            $result['success'] = '上传成功！';
            $imgurl = $info['Filedata']['savepath'].$info['Filedata']['savename'];
            $result['imgurl'] = $imgurl;
        }
        echo json_encode($result);
    }

    /**
     * 修改焦点图
     * 2014-12-19
     */
    public function editAdvert(){
        $this->checkAuth('Advert','editAdvert');
        if(empty($_POST)){
            $where['ad_id'] = I('get.ad_id');
            $ad = $this->ad_obj->findAdvert($where);
            if($ad){
                //焦点图位置
                $this->assign('ad',$ad);
                $this->display('editAdvert');
            }else{
                $this->error('该焦点图不存在或被删除');
            }
        }else{
            $where['ad_id'] = I('post.ad_id');
            $data = $this->ad_obj->create();
            if($data){
                //是否上传了焦点图图片
                if(!empty($_FILES['ad_pic']['name'])){
                    //上传焦点图图片
                    $data['ad_pic'] = $this->uploadImg();
                }
                $upd_res = $this->ad_obj->editAdvert($where,$data);
                if($upd_res){
                    $this->success('编辑焦点图成功',cookie("EDIT_BACK"));
                }else{
                    $this->error('编辑焦点图失败');
                }
            }else{
                $this->error($this->ad_obj->getError());
            }
        }
    }

    /**
     * 删除操作
     */
    public function deleteAdvert(){
        $this->checkAuth('Advert','deleteAdvert');
        if(empty($_REQUEST['ad_id'])){
            $this->error('您未选择任何操作对象');
        }
        $where['ad_id'] = array('IN',I('request.ad_id'));
        $data['status'] = 9;
        $data['utime'] = time();
        $upd_res = $this->ad_obj->editAdvert($where,$data);
        if($upd_res){
            $this->success('删除操作成功');
        }else{
            $this->error('删除操作失败');
        }
    }

    /**
     * 上传焦点图图片
     */
    public function uploadImg(){
        $res = uploadFile('','Advert');
        if(empty($res['error'])){
            return $res['success'];
        }else{
            $this->error($res['error']);
        }
    }

    /**
     * 编辑排序
     */
    public function editSort(){
        //修改条件 ID
        $where['ad_id'] = I('post.id');
        $data['sort_order'] = I('post.sort');
        //修改操作
        $upd_res = $this->ad_obj->editAdvert($where,$data);
        if($upd_res){
            $this->ajaxMsg('success','修改排序成功');
        }else{
            $this->ajaxMsg('error','修改排序失败，可能是未改变排序值');
        }
    }
}