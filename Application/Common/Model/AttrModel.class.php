<?php
namespace Common\Model;
use Think\Model;

/**
 * Class CaseModel
 * @package Common\Model
 */
class AttrModel extends Model {

    public function addattr($data){
        if(empty($data)){
            return false;
        }else{
            $result = $this->data($data)->add();
            return $result;
        }
    }
}