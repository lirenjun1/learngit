<?php
namespace Common\Model;
use Think\Model;

/**
 * Class CaseModel
 * @package Common\Model
 */
class CmdinfoModel extends Model {

    public function add($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
}