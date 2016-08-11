<?php
namespace Admin\Controller;
use Think\Controller;
/**
*  Class OnlineController
*  @package Admin\Controller
*  2016-6-14
*/

class OrderController extends Controller
{
    /**
     * 4A订单列表页
     * 2016-6-14
     */
    public function orderlist()
    {
        $order = M('order');
        import("ORG.Util.Page"); // 导入分页类
        $count = $order->table(array('toocms_4a'=>'4a','toocms_order'=>'order'))->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count,10);
        $page->setConfig('theme', $this->setPageTheme());
        $page_info = $page->show();
        $this->assign('page',$page_info);// 赋值分页输出
        $list  = $order->table(array('toocms_4a'=>'4a','toocms_order'=>'order'))->where('order.b_id=4a.id')->order('orderstatus asc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);// 赋值数据集
        // 渲染
        $this->display('orderlist');
    }

    /**
     * 4A订单详情页
     * 2016-6-14
     */
    public function editOrder(){
        $id = I('id');
        // 实例化创意人表
        $originality = M('originality');
        // 实例化订单详情表
        $order = M('order');

        $list = $order->where("id = ".$id)->select();

        // $user = $originality->field('people_id,people_name')->where("auditpass = 1")->select();
        $this->skills = json_encode($originality->field('people_id,people_name')->where("auditpass = 1")->select());
        $this->assign('user',$user);// 赋值数据集
        $this->assign('list',$list['0']);// 赋值数据集
        $this->display('editOrder');
    }

    /**
     *  稿件列表页
     *  2016.6.15
     */
    public function manuscript()
    {
        $where['x_id'] = I('id');
        $manuscript = M('manuscript');
        $order = M('order');

        $script = $manuscript->where($where)->order('g_status desc')->select();
        $x_name = $order->where("id = ".$where['x_id'])->field('ordersubject')->select();

        $this->assign('name',$x_name);// 赋值数据集
        $this->assign('script',$script);// 赋值数据集
        $this->display('manuscript');
    }

    /**
     * 稿件详情页
     * 2016-6-16
     */
    public function manuscriptdetails()
    {
        $order = M('order');
        $where['g_id'] = I('id');
        $manuscript = M('manuscript');
        $script = $manuscript->where($where)->select();
        // 获取项目名称
        $x_id = $script['0']['x_id'];
        $name = $order->where("id = ".$x_id)->field('ordersubject')->select();
        $this->assign('name',$name);

        $this->assign('script',$script['0']);

        $this->display('manuscriptdetails');
    }

    /**
     * 稿件通过审核
     * 2016.6.16
     */
    public function review(){
        // 获取稿件ID
        $where['g_id'] = I('id');
        $data['state'] = 1;
        // 实例化
        $manuscript = M('manuscript');
        $manus = $manuscript->where($where)->save($data);
        if($manus){
            $this->success('修改成功');
        }else{
            $this->error('本次修改失败');
        }
    }


    /**
     * 稿件修改 modify
     * 2016.6.17
     */
    public function modify()
    {
        // 实例化
        $manuscript = M('manuscript');
        // 修改条件
        $where['g_id'] = $_POST['g_id'];

        // 修改的信息
        $data['manuscriptname'] = $_POST['manuscriptname'];
        $data['feedback'] = $_POST['feedback'];
        $data['the_a'] = $_POST['the_a'];
        $data['the_b'] = $_POST['the_b'];
        $script = $manuscript->where($where)->save($data);
        if ($script) {
            $result['success'] = "移除成功！";
        } else {
            $result['success'] = "移除失败！";
        }
        echo json_encode($result);
    }

    /**
     * 4A订单发布及修改
     * 2016.6.16
     */
    public function revision()
    {
        $where['id'] = $_POST['id'];
        $data['ordersubject'] = $_POST['ordersubject'];
        $data['ordertype'] = $_POST['ordertype'];
        $data['orderchannel'] = $_POST['orderchannel'];
        $data['projectbackground'] = $_POST['projectbackground'];
        $data['copystatus'] = $_POST['copystatus'];
        $data['styleandtone'] = $_POST['styleandtone'];
        $data['elements'] = $_POST['elements'];
        $data['eliminateelements'] = $_POST['eliminateelements'];
        $data['ordercolor'] = $_POST['ordercolor'];
        $data['picturesize'] = $_POST['picturesize'];
        $data['bleedingsize'] = $_POST['bleedingsize'];
        $data['distinguish'] = $_POST['distinguish'];
        $data['filesize'] = $_POST['filesize'];
        $data['recommend'] = $_POST['recommend'];
        $data['orderstatus'] = 1;

        $order = M('order');
        $rder = $order->where($where)->save($data);
        if($rder){
            $result['success'] = "修改成功！";
        }else{
            $result['error'] = "修改失败！";
        }
        echo json_encode($result);
    }

    /**
     *  稿件完成稿
     *  2016.6.20
     */
    public function complete()
    {
        $where['g_id'] = I('id');
        $manuscript = M('manuscript');
        $order = M('order');

        $script = $manuscript->where($where)->select();
        $x_id = $script['0']['x_id'];
        $x_name = $order->where("id = ".$x_id)->field('ordersubject')->select();

        $this->assign('name',$x_name);// 赋值数据集
        $this->assign('script',$script['0']);// 赋值数据集
        $this->display('complete');
    }


    private function setPageTheme()
    {
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }
}