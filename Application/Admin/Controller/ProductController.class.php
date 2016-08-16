<?php

namespace Admin\Controller;
use Org\Util\Date;
use Think\Controller;

/**
 * Class AdminController
 * @package Admin\Controller
 * 2014-8-18  add by <黑暗中的武者>
 */
class ProductController extends AdminBasicController{
    public function productlist(){
        $this->display();
    }
    public function productadd(){
        //查询产品类型
        $model=D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
        $prt=$model->field()->select();
        $this->assign('prt',$prt);
        //查询商家
        $sj=D('Common/Shangjia');
        $shj=$sj->field()->select();
        $this->assign('shj',$shj);
        $request=I('get.r');
        if(empty($request)){
            $this->display();
        }elseif($request=='add'){
            $posts=I('post.');
            //$img=$_FILES['imgs'];

            $dir='tes';
            $img=$this->upload($dir);
            var_dump($img);exit();
            $photo=$_FILES['photo'];

            foreach ($photo as $k=>$v){

                $p=$this->upload1($v,$dir);

            }

            exit;
            $p=$this->upload1($img,$dir);
            $p2=$this->upload($dir);
            var_dump($p2);
            echo  $p;
//            $photo=$_FILES['photo'];
//            var_dump($posts['attr']);
//            var_dump($posts['attr_name']);
//            var_dump($posts['attr_price']);
//            $dir='test';
//            $img=$this->upload($photo,$dir);
//            print_r($img);exit;
        }

    }
    public function  producttype(){
        $model=D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
        $prt=$model->field()->select();
        $this->assign('prt',$prt);
        $this->display();
    }
    public function typeadd(){
        $model=D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
        $prt=$model->field()->select();
        $this->assign('prt',$prt);
        $request=I('get.r');
        if(empty($request)){
            $this->display();
        }elseif($request=='add'){
            $posts=I('post.');
            $file=$_FILES['img'];
             $dir='type';
            $img=$this->upload1($file,$dir);
            if($img=="false"){
                echo "<script> alert('上传文件失败，请再次操作');</script>";
                $this->display();
            }else{
                $posts['cmd_logo']=$img;
                $msg=$model->addtype($posts);
                if($msg==false){
                    echo "<script> alert('添加失败，请再次操作');</script>";
                    $this->display();
                }else{
                    echo "<script> alert('添加成功');</script>";
                    $this->redirect('Product/producttype', array(), 0, '页面跳转中...');

                }
            }


        }

    }
    public function productattr(){
        $this->display();

    }
    public function attradd(){
        $model=D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
        $prt=$model->field()->select();
        $this->assign('prt',$prt);
        $request=I('get.r');
        if(empty($request)){
            $this->display();
        }elseif($request=='add'){
            $posts=I('post.');
            $attr=D('Common/Attr');
            $msg=$attr->addattr($posts);
            if($msg==false){
                echo "<script> alert('添加失败，请再次操作');</script>";
                $this->display();
            }else{
                echo "<script> alert('添加成功');</script>";
                $this->display('productattr');
            }
        }
        
    }

    public function ajax(){
        $typeid=I('post.id');
        $msg['typeid']=$typeid;
        $model=D('Common/Attr');
        $data=$model->where($msg)->field('id,name')->select();
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);

//        $data =$this->JSON($data);
        $this->ajaxReturn($data,'JSON');
    }

}