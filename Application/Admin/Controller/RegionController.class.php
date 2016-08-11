<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class RegionController
 * @package Admin\Controller
 */
class RegionController extends AdminBasicController{
    public $region_obj = '';
    public function _initialize(){
        parent::_initialize();
        $this->region_obj = D('EcsRegion');
    }

    /**
     * 新增区域
     */
    public function addRegion(){
        $this->checkAuth('Region','addRegion');
        if(empty($_POST)){
            $this->display('addRegion');
        }else{
            $data = $this->region_obj->create();
            if($data){
                $data['parent_id'] = 3409;
                $data['region_type'] = 1;
                $add_res = $this->region_obj->addRegion($data);
                if($add_res){
                    $this->success('添加成功',U('Region/regionList'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error($this->region_obj->getError());
            }
        }
    }

    /**
     * 区域列表
     */
    public function regionList(){
        $where['parent_id'] = 3409;//3409 表示国外的父级地址
        $region_list = $this->region_obj->selectRegion($where,'',$this->getPageNumber());
        if($region_list['list']){
            $this->assign('region_list',$region_list['list']);
            $this->assign('page',$region_list['page']);
        }
        $this->display('regionList');
    }
    /**
     *编辑区域列表
     */
    public function editRegion(){
        $this->checkAuth('Region','editRegion');
        if(empty($_POST)){
            $where['region_id'] = I('get.region_id');
            $region_info = $this->region_obj->findRegion($where);
            $this->assign('region_info',$region_info);
            $this->display('editRegion');
        }else{
            $where['region_id'] = I('post.region_id');
            $data['region_name']  = I('post.region_name');
            $upd_res = $this->region_obj->editRegion($where,$data);
            if($upd_res){
                $this->success('修改成功',U('Region/regionList'));
            }else{
                $this->error('修改失败');
            }
        }
    }
    /**
     * 删除区域列表
     */
    public function deleteRegion(){
        $this->checkAuth('Region','deleteRegion');
        $where['region_id'] = I('get.region_id');
        $del_upd = $this->region_obj->deleteRegion($where);
        if($del_upd){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
}