<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2016/2/19
 * Time: 11:58
 */

namespace Common\Model;
use Think\Model;


class WorkLikeModel extends Model{
    protected $tableName = 'work_like';

    /**
     * @var array自动验证
     */
    protected $_validate =array(
        array('work_id','require','数据请求错误！'),
        array('content','require','留言内容不能为空！'),
    );

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
    );

    public function addLike($where,$isLike)
    {
        if (empty($where)) {
            return false;
        } else {
            $res = $this->where($where)->save(array('isLike' => $isLike, 'ctime' => time()));
            if($res){
                return $res;
            } else{
                $where['isLike'] = $isLike;
                if ($this->create($where)) {
                    $res = $this->add();
                    return $res;
                }
            }
            return false;
        }
    }

    public function deleteLike($where){
        if(empty($where)){
            return false;
        }
        $result = $this->where($where)->delete();
        return $result;
    }

    public function isLike($where){
        if(empty($where)){
            return false;
        }
        $result = $this->field("isLike")->where($where)->find();
        return $result?$result["isLike"]:3;
    }

    public function getLikeCount($where){
        if(empty($where)){
            return false;
        }
        $where['isLike'] = 0;
        $result = $this->where($where)->count();
        return $result;
    }

    public function getNLikeCount($where){
        if(empty($where)){
            return false;
        }
        $where['isLike'] = 1;
        $result = $this->where($where)->count();
        return $result;
    }
}