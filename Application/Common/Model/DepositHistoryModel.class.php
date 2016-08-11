<?php
namespace Common\Model;
use Think\Model;
/**
 * Class DepositHistoryModel
 * @package Common\Model
 */
class DepositHistoryModel extends Model {
    const recharge  = 0;    //充值
    const pay_task = 1;     //脑暴发布
    const refund_task = 2;     //脑暴退款
    const bonus_idea = 3;   //脑洞分红
    //const bonus_idea_gain = 4;
    const withdraw = 5;     //提现

    const status_nopay  = 0;    //未支付、提现审核中
    const status_alipay_fail = 1;     //支付宝支付失败、正在提现
    const status_balance_fail = 2;    //余额支付失败、提现驳回
    const status_alipay_succ = 3;     //支付宝支付成功、提现失败
    const status_balance_succ = 4;    //余额支付成功、提现成功
    const status_balance_fenhong = 5;    //网站脑洞分红


    protected $tableName = 'deposit_history';
    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    //获取脑暴总收入
    public function sumMoney($where){
        if(empty($where)){
            return false;
        }
        $result = $this->where($where)->sum('amount');
        return $result;
    }

    /**
     * 查询多条数据
     */
    public function selectDepositHistory($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($page_size == ''){
            $result = $this->where($where)->order($order)->select();
        }else{
            $count = $this->where($where)->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow,$page_size)
                ->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
        }
        return $result;
    }

    /**
     * 查询多条数据 账单
     */
    public function selectDepositHistoryOrder($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['order_delete'] == ''|| empty($where['order_delete'])){
            $where['order_delete'] = array('neq',9);
        }
        if($page_size == ''){
            $result = $this->where($where)->order($order)->select();
        }else{
            $count = $this->where($where)->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow,$page_size)
                ->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
        }
        return $result;
    }

    /**
     * 查询多条数据 站内信
     */
    public function selectDepositHistoryMessage($where = array(),$order = '',$page_size = '',$parameter = array()){
        if($where['msg_status'] == ''|| empty($where['msg_status'])){
            $where['msg_status'] = array('neq','删除');
        }
        if($page_size == ''){
            $result = $this->where($where)->order($order)->select();
        }else{
            $count = $this->where($where)->count();
            $page = new \Think\Page($count,$page_size);
            $page->parameter = $parameter;
            $page->setConfig('theme',$this->setPageTheme());
            $page_info =$page->show();
            $list = $this->where($where)
                ->order($order)
                ->limit($page->firstRow,$page_size)
                ->select();
            $result = array('page'=>$page_info,'list'=>$list,'count'=>$count);
        }
        return $result;
    }


    public function getDepositHistoryCount($where){
        if(empty($where)){
            return 0;
        }
        if($where['msg_status'] == ''|| empty($where['msg_status'])){
            $where['msg_status'] = array('neq','删除');
        }
        return $this->where($where)->count();
    }

    /*
     * 将阅读状态设置为1
     */
    public function setIsRead($id_lsit){
        $where['deposit_history_id'] = array('in',$id_lsit);
        $data['msg_status'] = '已读';
        if(empty($data) || empty($where)){
            return false;
        }else{
            if($res = $this->where($where)->save($data)){
                return $res;
            }
            return false;
        }
    }

    /*
    * 将阅读状态设置为9
    */
    public function delMsg($where){
        $data['msg_status'] = '删除';
        if(empty($data) || empty($where)){
            return false;
        }else{
            if($res = $this->where($where)->save($data)){
                return $res;
            }
            return false;
        }
    }

    /**
     * 添加数据
     */
    public function addDepositHistory($data){
        if(empty($data)){
            return false;
        }
        if($this->create($data)){
            $result = $this->add();
            return $result;
        }
        return false;
    }

    /**
     * 编辑数据
     */
    public function saveDepositHistory($where,$data){
        if(empty($where) || empty($data)){
            return false;
        }
        if($where['order_delete'] == ''|| empty($where['order_delete'])){
            $where['order_delete'] = array('neq',9);
        }
        if($data['msg_status'] == ''|| empty($data['msg_status'])){
            $data['msg_status'] = '未读';
        }
        if($this->create($data,2)){
            $result = $this->where($where)->save();
            return $result;
        }
        return false;
    }

    /**
     * 查询一条数据
     */
    public function findDepositHistory($where){
        if(empty($where)){
            return false;
        }else{
            if($where['order_delete'] == ''|| empty($where['order_delete'])){
                $where['order_delete'] = array('neq',9);
            }
            $result = $this->where($where)->find();
            return $result;
        }
    }

    //删除订单
    public function deleteOrder($where){
        if(empty($where)){
            return false;
        }
        $data['order_delete'] = 9;
        $result = $this->where($where)->save($data);
        return $result;
    }
    /*
     * 统计未读交易条数
     */
    public function countRead($uid,$t){
        $sql = "SELECT COUNT(*) num FROM toocms_deposit_history WHERE m_id=$uid AND msg_status='未读'";
        if($t){
            $sql.= " AND ctime > $t";
        }
        $result = $this->query($sql);
        $result = $result[0]['num'];
        return $result;
    }

    /**
     * 分页样式
     */
    private function setPageTheme(){
        $theme = "<ul class='pagination'><li>%TOTAL_ROW%</li><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li></ul>";
        return $theme;
    }

}