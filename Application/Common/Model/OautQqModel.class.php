<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2016/1/19
 * Time: 14:06
 */

namespace Common\Model;
use Think\Model;


class OautQqModel extends Model{
    protected $tableName = 'oaut_qq';

    /**
     * @param $data
     */
    public function checkLogin($data){
        $where['qq_id'] = $data['qq_id'];
        $res = $this->field('m_id')->where($where)->find();
        if($res){
            $mem_obj = D('Member');
            $mem = $mem_obj->field('m_id,m_head,m_account,m_email')->findMember($res);
            if($mem){
                session('M_ID',$mem['m_id']);
                session('M_ACCOUNT',$mem['m_account']);
                session('M_HEAD',$mem['m_head']);
                session('M_EMAIL',$mem['m_email']);
                $this->where($where)->data($data)->save();
            }else{
                $_SESSION['OAUT_QQ'] = $data;
            }
        }else{
            $_SESSION['OAUT_QQ'] = $data;
        }
    }

    public function addQq($m_id){
        $data = $_SESSION['OAUT_QQ'];
        $data['m_id'] = $m_id;
        $this->data($data)->add();
        unset($_SESSION['OAUT_QQ']);
    }
}