<?php
/**
 * Created by PhpStorm.
 * User: theonead
 * Date: 10/8 0008
 * Time: 16:15
 */
namespace Common\Model;
use Think\Model;

/**
 * Class ProviderMasterModel
 * @package Common\Model
 */
class ProviderModel extends Model {
    protected $tableName = 'provider';
    /**
     * @var array
     * �Զ����   ����ʱ
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // ��ctime�ֶ��ڲ����ʱ��д�뵱ǰʱ���
        array('utime','time',self::MODEL_BOTH,'function'), // ��utime�ֶ����޸ĵ�ʱ��д�뵱ǰʱ���
    );

    /**
     * ��ѯһ������
     */
    public function findProvider($where){
        if(empty($where)){
            return false;
        }else{
            $result = $this->where($where)->find();
            return $result;
        }
    }
    /**
     * ��ѯ��������
     */
    public function selectProvider($where){
        if(empty($where)){
            return false;
        }else{
            $result = $this->where($where)->select();
            return $result;
        }
    }
    /**
     * ��ȡ���ݿ��ѯ���
     */
    public function getLastSSql(){
        return $this->getLastSql();
    }
}

?>