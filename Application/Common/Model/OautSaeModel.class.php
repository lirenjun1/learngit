<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2016/1/19
 * Time: 14:06
 */

namespace Common\Model;
use Think\Model;


class OautSaeModel extends Model{
    protected $tableName = 'oaut_sae';

    public function checkLogin($data){
        $where['sae_id'] = $data['sae_id'];
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
                $_SESSION['OAUT_SAE'] = $data;
            }
        }else{
            $_SESSION['OAUT_SAE'] = $data;
        }
    }

    public function addSae($m_id){
        $data = $_SESSION['OAUT_SAE'];
        $data['m_id'] = $m_id;
        if($this->create($data)){
            $res = $this->add();
        }
        unset($_SESSION['OAUT_SAE']);
    }
}