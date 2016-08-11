<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class SkillTypeController
 * @package Admin\Controller
 * 服务商技能类
 * add by cml
 */
class SkillTypeController extends AdminBasicController{
    public $sk_obj = '';
    public function _initialize(){
        parent::_initialize();
        $this->sk_obj = D('SkillType');
    }
    /**
     *新增服务商技能
     */
    public function addSkillType(){
        $this->checkAuth('SkillType','addSkillType');
        if(empty($_POST)){
            $this->display('addSkillType');
        }else{
            $data = $this->sk_obj->create();
            if($data){
                $data['status'] = 0;
                $add_res = $this->sk_obj->addSkillType($data);
                if($add_res){
                    $this->success('添加服务商技能类别成功',U('SkillType/skillTypeList'));
                }else{
                    $this->error('添加服务商技能类别失败');
                }
            }else{
                $this->error($this->sk_obj->getError());
            }
        }
    }
    /**
     *服务商技能列表
     */
    public function skillTypeList(){
        $where['status'] = array('neq',9);
        $sk_list = $this->sk_obj->selectSkillType($where,'',$this->getPageNumber());
        if($sk_list['list']){
            $this->assign('sk_list',$sk_list['list']);
            $this->assign('page',$sk_list['page']);
        }
        $this->display('skillTypeList');
    }
    /**
     *修改服务商技能
     */
    public function editSkillType(){
        $this->checkAuth('SkillType','editSkillType');
        if(empty($_POST)){
            $where['sk_id']  = I('sk_id');
            $where['status'] = array('neq',9);
            $sk_info = $this->sk_obj->findSkillType($where);
            if($sk_info){
                $this->assign('sk_info',$sk_info);
            }
            $this->display('editSkillType');
        }else{
            $data = $this->sk_obj->create();
            if($data){
                $where['sk_id'] = I('post.sk_id');
                $upd_res = $this->sk_obj->editSkillType($where,$data);
                if($upd_res){
                    $this->success('修改成功',U('SkillType/skillTypeList'));
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error($this->sk_obj->getError());
            }
        }
    }
    /**
     *删除服务商技能
     */
    public function deleteSkillType(){
        $this->checkAuth('SkillType','deleteSkillType');
        $where['sk_id'] = I('get.sk_id');
        $data['status'] = 9;
        $del_res = $this->sk_obj->editSkillType($where,$data);
        if($del_res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}