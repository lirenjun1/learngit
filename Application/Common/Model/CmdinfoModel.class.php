<?php
namespace Common\Model;
use Think\Model;

/**
 * Class CaseModel
 * @package Common\Model
 */
class CmdinfoModel extends Model {
    protected $_validate = array(
        array('cmdinfo_no','','添加失败',0,'unique',1), // 在新增的时候验证name字段是否唯一
    );

    public function addp($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
}