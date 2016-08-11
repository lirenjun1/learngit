<?php
/**
 * Created by PhpStorm.
 * User: theone
 * Date: 2015/12/2
 * Time: 14:26
 */

namespace Common\Model;


class ThumbsModel extends Model {

    protected $tableName = 'thumbs';

    /**
     * @var array
     * 自动完成   新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );
}