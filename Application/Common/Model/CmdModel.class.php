<?php
namespace Common\Model;
use Think\Model;

/**
 * Class CaseModel
 * @package Common\Model
 */
class CmdModel extends Model {

    public function addtype($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
}