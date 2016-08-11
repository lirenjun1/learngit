<?php
namespace Admin\Controller;
use Think\Controller;
/**
*  Class OnlineController
*  @package Admin\Controller
*  2016-6-13
*/

class OnlineController extends AdminBasicController
{
    /**
     * 4A用户列表页
     * 2016-6-13
     */
    public function Onlinelist()
    {

        // 实例化
        $user = M('4a');
        // 条件
        $where = "status = 0";
        import("ORG.Util.Page"); // 导入分页类
        $count = $user->where($where)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出

        // 查询
        $online = $user->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        // $online = $user->where($where)->select();
        $this->assign('online',$online);
        // 渲染
        $this->display('Onlinelist');
    }

    /**
     * 添加用户视图
     * 2016-6-13
     */
    public function addOnline()
    {
        // 渲染模板
        $this->display('addOnline');
    }

    /**
     * 添加4A用户
     * 2016-6-13
     */
    public function Onlineadd()
    {
        $data['b_name'] = $_POST['b_name'];
        $data['cellphone'] = $_POST['cellphone'];
        $data['email'] = $_POST['email'];
        $data['password'] = md5($_POST['password']);
        $data['r_time'] = time();
        $user = M('4a');
        $list = $user->add($data);
        if($list){
            $this->success('添加4A用户成功');
        }else{
            $this->error('添加4A用户失败');
        }
       
    }

    /**
     * 修改4A用户视图、
     * 2016-6-13
     */
    public function editonline()
    {
        $user = M('4a');
        $where['id'] = I('get.id');
        $online = $user->where($where)->select();
        $this->assign('online',$online);
        $this->display('editonline');
    }

    /**
     * 执行4A用户修改、
     * 2016-6-13
     */
    public function saveonline(){
        $user = M('4a');
        $where['id'] = I('id');
        $data['b_name'] = $_POST['b_name'];
        $data['cellphone'] = $_POST['cellphone'];
        $data['email'] = $_POST['email'];
        $data['dosage'] = $_POST['dosage'];
        $data['grade'] = $_POST['grade'];
        $online = $user->where($where)->save($data);
        if($online){
            $this->success('修改成功');
        }else{
            $this->error('本次修改失败');
        }

    }

	/**
     * 删除4A用户、
     * 2016-6-13
     */
    public function deleteOnline(){
        $user = M('4a');
        $where['id'] = I('get.id');
        $data['status'] = 9;
        $upd_res = $user->where($where)->save($data);
        if($upd_res){
            $this->success('删除成功');
        }else{
            $this->error('本次删除失败');
        }
    }

    /**
     * 套餐信息、
     * 2016-6-14
     */
    public function package(){
        $package = M('package');
        $where['id'] = I('b_id');

        // 连表查询
        $online  = $package->table(array('toocms_4a'=>'4a','toocms_package'=>'pa'))->where('pa.b_id=4a.id AND b_id='.$where['id'])->select();
        /*$id = I('get.b_id');
        $where['b_id'] = I('get.b_id');
        // 查询的用户名称
        $name = $user->where("id=".$id)->field("b_name")->select();
        $this->assign('name',$name);
        // 查询套餐信息
        $online = $package->where($where)->select();
        $online['0']['b_name'] = $name['0']['b_name'];*/
        $this->assign('online',$online);
        $this->display('package');
    }

    /**
     * 套餐添加
     * 2016-6-20
     */
    public function addpackage()
    {
        $id = I('b_id');
        $user = M('4a');
        $name = $user->where("id=".$id)->field("id,b_name")->select();
        $name['time'] = time();
        $this->assign('name',$name);
        // 渲染模板
        $this->display('addpackage');
    }


    /**
     * 执行添加套餐
     * 2016-6-20
     */
    public function packageadd()
    {
        // 实例化
        $package = M('package');
        // 4A用户的ID
        $data['b_id'] = $_POST['id'];

        $data['type'] = $_POST['type'];
        if($_POST['type'] == 1){
            $data['total'] = 4;
            $data['surplus'] = 4;
        }
        if($_POST['type'] == 2){
            $data['total'] = 6;
            $data['surplus'] = 6;
        }
        if($_POST['type'] == 3){
            $data['total'] = 12;
            $data['surplus'] = 12;
        }
        if($_POST['type'] == 4){
            $data['total'] = 1;
            $data['surplus'] = 1;
        }
        $data['status'] = 1;
        $data['duetime'] = $_POST['duetime'];
        $data['b_time'] = time();

        $pack = $package->add($data);

        if($pack){
            $result['success'] = "添加成功！";
            $result['b_id'] = $data['b_id'];
        }else{
            $result['error'] = "添加失败！";
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