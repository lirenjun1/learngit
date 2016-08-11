<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 文章控制类
 * 薛晓峰
 * 2014-12-19   
 */
class ArticleController extends AdminBasicController{
    protected  $prohibit = array();   //禁止进行操作的分类数组

    public $article_obj = '';
    public $cate_obj    = '';
	public function _initialize(){
        $this->checkLogin();
		$this->article_obj = D('Article');
        $this->cate_obj    = D('ArticleCate');
	}

    /**
     * 文章列表
     * 2014-12-19
     */
    public function articleList(){
        if(!empty($_REQUEST['title'])){
            $where['title'] = array('LIKE',"%".I('request.title')."%");
            $parameter['title'] = $_REQUEST['title'];
        }
        //文章列表
        $article_list = $this->article_obj->selectArticle($where,'ctime desc',$this->getPageNumber(),$parameter);
        if($article_list['list']){
            $this->assign('article_list',$article_list['list']);
            $this->assign('page',$article_list['page']);
        }
        //编辑后返回
        $this->setEditBack(U('Article/articleList',$_REQUEST));
        $this->display('articleList');
	}

    /**
     * 添加文章
     */
    public function addArticle(){
        $this->checkAuth('Article','addArticle');
        if(empty($_POST)){
            //获取分类
            $this->assign('cate_sel',$this->_categorySelect('',''));
            $this->display('addArticle');
        }else{
            $data = $this->article_obj->create();
            if($data){
                //替换掉 换行
                $data['content']     = str_replace("\n", "<br>",$_POST['content']);
                //替换掉 空格
                $data['content']     = str_replace(" ", "&nbsp;",$data['content']);
                $data['author']    = session('A_ACCOUNT');
                $data['recommend'] = serialize(I('post.recommend'));
                if(!empty($_FILES['pic_url']['name'])){
                     $data['pic_url'] = $this->uploadImg();
                }
                $add_res = $this->article_obj->addArticle($data);
                if($add_res){
                    $this->success('添加文章成功',U('Article/articleList'));
                }else{
                    $this->error('添加文章失败');
                }
            }else{
                $this->error($this->article_obj->getError());
            }
        }
	}

    /**
     * 若文章分类有子分类则不能添加文章 或被禁止添加
     */
    public function ajaxJudge(){
        $cate = $this->cate_obj->findArticleCate(array('parent_id'=>I('post.cate_id')));
        if($cate || !empty($this->prohibit) && in_array(I('post.cate_id'),$this->prohibit)){
            $this->ajaxMsg('error','禁止对该分类进行当前操作');
        }else{
            $this->ajaxMsg('success','ok');
        }
    }
    /**
     * 编辑文章
     */
    public function editArticle(){
        $this->checkAuth('Article','editArticle');
		if(empty($_POST)){
			$article = $this->article_obj->findArticle(array('article_id'=>I('get.article_id')));
            //textarea与HTML 内容转换
            if(strpos($article['content'],'<br>')){
                //删除既有的br
                $article['content'] = str_replace("<br>", "",$article['content']);
            }
            if($article){
                //该文章是否可修改分类
                if(in_array($article['cate_id'],$this->prohibit)){
                    $this->assign('prohibit','1');
                }
                //推荐位数组
                $article['recommend'] = unserialize($article['recommend']);
                $this->assign('article',$article);
                //获取分类
                $this->assign('cate_sel',$this->_categorySelect($article['cate_id'],'cate_id'));
                $this->display('editArticle');
            }else{
                $this->error('该文章不存在或已删除');
            }
		}else{
            $data = $this->article_obj->create();
            if($data){
                $where['article_id'] = I('post.article_id');

                //替换掉 换行
                $data['content']     = str_replace("\n", "<br>",$_POST['content']);
                //替换掉 空格
                $data['content']     = str_replace(" ", "&nbsp;",$data['content']);
                $data['author']      = session('A_ACCOUNT');
                $data['recommend']   = serialize(I('post.recommend'));
                if(!empty($_FILES['pic_url']['name'])){
                     $data['pic_url'] = $this->uploadImg();
                }
                $upd_res = $this->article_obj->editArticle($where,$data);
                if($upd_res){
                    $this->success('编辑文章成功',cookie("EDIT_BACK"));
                }else{
                    $this->error('编辑文章失败');
                }
            }else{
                $this->error($this->article_obj->getError());
            }
		}
	}

    /**
     * 删除操作
     */
    public function deleteArticle(){
        $this->checkAuth('Article','deleteArticle');
        if(empty($_REQUEST['article_id'])){
            $this->error('您未选择任何操作对象');
        }
        $where['article_id'] = array('IN',I('request.article_id'));
        $data['status'] = 9;
        $data['utime'] = time();
        $upd_res = $this->article_obj->editArticle($where,$data);
        if($upd_res){
            //其他删除操作
            $this->success('删除操作成功');
        }else{
            $this->error('删除操作失败');
        }
    }

    /**
     * 上传文章封面图片
     */
    public function uploadImg(){
        $res = uploadFile('','Article');
        if(empty($res['error'])){
            return $res['success'];
        }else{
            $this->error($res['error']);
        }
    }

    /**
     * 批量移动
     */
    public function moveArtBatch(){
        $this->checkAuth('Article','editArticle');
        if(empty($_POST['article_id'])){
            $this->error('您未选择任何操作对象');
        }
        $where['article_id'] = array('IN',I('post.article_id'));
        $data['cate_id'] = I('post.cate_id');
        $data['utime'] = time();
        $upd_res = $this->article_obj->editArticle($where,$data);
        if($upd_res){
            //分类名称
            $cate_name = $this->cate_obj->where(array('cate_id'=>I('post.cate_id')))->getField('cate_name');
            $this->success('所有目标已批量移动至【'.$cate_name.'】');
        }else{
            $this->error('批量移动失败');
        }
    }

    /**
     * 生成 分类的下拉菜单
     * */
    private function _categorySelect($val,$index){
        $select_tmp = '<option value="{cate_id}">{nbsp}{cate_name}</option>';
        $res = $this->cate_obj->getAllCategory(0,true);
        $tree = new \Think\Tree();
        $tree->__init($res,'child',$select_tmp,array('cate_id','cate_name'));
        $select = '<select name="cate_id" class="form-control input-sm"><option value="0">--顶级分类--</option>'.$tree->getSelectTree($val,$index).'</select>';
        return $select;
    }

    /**
     * 编辑文章是否显示
     */
    public function isShowOperate(){
        //修改条件 ID
        $where['article_id'] = I('post.id');
        //判断修改为显示或者不显示
        if(I('post.flag') == 1){
            $data['is_open'] = 0;
        }elseif(I('post.flag') == 0){
            $data['is_open'] = 1;
        }
        //修改操作
        $upd_res = $this->article_obj->editArticle($where,$data);
        if($upd_res){
            $this->ajaxMsg('success','编辑成功');
        }else{
            $this->ajaxMsg('error','编辑失败');
        }
    }

    /**
     * 编辑排序
     */
    public function editSort(){
        //修改条件 ID
        $where['article_id'] = I('post.id');
        $data['sort_order'] = I('post.sort');
        //修改操作
        $upd_res = $this->article_obj->editArticle($where,$data);
        if($upd_res){
            $this->ajaxMsg('success','修改排序成功');
        }else{
            $this->ajaxMsg('error','修改排序失败，可能是未改变排序值');
        }
    }
}
