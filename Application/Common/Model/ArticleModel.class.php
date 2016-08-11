<?php
namespace Common\Model;
use Think\Model;

/**
* 文章信息
* 薛晓峰
* 2014-12-19
*/
class ArticleModel extends Model{
	protected $tableName = "article";

	/**
     * 自动验证 使用create()方法时自动调用
     */
	protected $_validate = array(
		array('title','require','标题不能为空！'), //空验证  默认情况下用正则进行验证
		array('title','0,20','标题在20个字以内！',self::EXISTS_VALIDATE,'length'),
		array('cate_id','0','请选择文章分类！',self::EXISTS_VALIDATE,'notequal'),
		array('content','require','内容不能为空！'),
	);
	
	/**
     * 自动完成 新增时
     */
    protected $_auto = array (
        array('ctime','time',self::MODEL_INSERT,'function'), // 对ctime字段在插入的时候写入当前时间戳
        array('utime','time',self::MODEL_BOTH,'function'), // 对utime字段在修改的时候写入当前时间戳
    );

    /**
     * 查询全部文章
     */
    public function selectArticle($where = array(),$order = '',$page_size = '',$parameter = array()){
    	if($where['status'] == '' || empty($where['status'])){
    		$where['status'] = array('neq',9);
    	}
    	if($page_size == ''){
    		$result = $this->where($where)->order($order)->select();
    	}else{
    		$count = $this->where($where)->count();
    		$page = new \Think\Page($count,$page_size);
    		$page->parameter = $parameter;
    		$page->setConfig('theme',$this->setPageTheme());
    		$page_info = $page->show();
    		$list = $this->where($where)->order($order)->limit($page->firstRow,$page_size)->select();
    		$result = array('page'=>$page_info,'list'=>$list);
    	}
        //获取分类名称
        $cate_obj = D('ArticleCate');
        foreach($result['list'] as $key => $value){
            $result['list'][$key]['cate_name'] = $cate_obj->where(array('cate_id'=>$value['cate_id']))->getField('cate_name');
        }
    	return $result;
    }

    /**
     * 查询一篇文章
     */	
    public function findArticle($where){
    	if(empty($where)){
            return false;
        }
        if($where['status'] == '' || empty($where['status'])){
    		$where['status'] = array('neq',9);
    	}
    	$result = $this->where($where)->find();
    	return $result;
    }

    /**
     * 增加文章
     */
    public function addArticle($data){
    	if(empty($data)){
    		return false;
    	}
    	$result = $this->data($data)->add();
    	return $result;
    }

    /**
     * 修改文章
     */
    public function editArticle($where,$data){
    	if(empty($where) || empty($data)){
    		return false;
    	}
    	$result = $this->where($where)->data($data)->save();
    	return $result;
    }

    /**
     * 删除文章
     */
    public function deleteArticle($where){
    	if(empty($where)){
    		return false;
    	}
    	$result = $this->where($where)->delete();
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

?>