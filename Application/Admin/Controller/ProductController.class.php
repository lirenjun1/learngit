<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * Class AdminController
 * @package Admin\Controller
 * 2014-8-18  add by <黑暗中的武者>
 */
class ProductController extends AdminBasicController{
    public function productlist(){
       $cmdinfo=D('Common/Cmdinfo');
       // $cmdinfo=M('Cmdinfo');
        $count =$cmdinfo->join('__CMD__ ON __CMDINFO__.cmd_id=__CMD__.cmd_id')
        ->join('__SHANGJIA__ ON __CMDINFO__.cmdinfo_sjno=__SHANGJIA__.id')
        //->field('__CMDINFO__.cmd_name,__CMDINFO__.cmdinfo_no,__CMDINFO__.cmdinfo.status,__CMDINFO__.cmdinfo_img,__CMDINFO__.cmdinfo_price,__CMD__.cmd_name,__SHANGJIA__.name')
        ->count();
      //  $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page = new \Org\Util\Page($count,10);

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $cmdinfo->join('__CMD__ ON __CMDINFO__.cmd_id=__CMD__.cmd_id')
            ->join('__SHANGJIA__ ON __CMDINFO__.cmdinfo_sjno=__SHANGJIA__.id')
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
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
        }elseif($request=='add')
        {
            $posts=I('post.');
            $productno=$this->randCode(10,0);
            $dir=$posts['cmdinfo_sjno'];
            $photo=$_FILES['photo'];
            $ph=$this->upload($dir);
            if($ph=='false'){
                echo "<script> alert('上传文件失败，请再次操作');</script>";
                $this->display();  
            }else{
                $cmdinfo['cmdinfo_name']=$posts['cmdinfo_name'];
                $cmdinfo['cmd_id']=$posts['cmd_id'];
                $cmdinfo['cmdinfo_sjno']=$posts['cmdinfo_sjno'];
                $cmdinfo['cmdinfo_br']=$posts['cmdinfo_br'];
                $cmdinfo['cmdinfo_price']=$posts['cmdinfo_price'];
                $cmdinfo['cmdinfo_no']=$productno;
                $cmdinfo['cmdinfo_img']=$ph[0];

                $cmdin=D('Common/Cmdinfo');
                if (!$cmdin->create()){
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    exit($User->getError());
                }else{
                    // 验证通过 可以进行其他数据操作

                $result=$cmdin->addp($cmdinfo);
                if($result=='fales'){
                    echo "<script> alert('添加失败');</script>";
                    $this->display();
                }else{
                    $attrm=D('Common/Proattr');
                    $length=count($posts['attr']);
                    for($i=0;$i<$length;$i++){
                        $data['attr']=$posts['attr'][$i];
                        $data['attr_name']=$posts['attr_name'][$i];
                        $data['attr_price']=$posts['attr_price'][$i];
                        $data['attr_kucun']=$posts['attr_kucun'][$i];
                        $data['productno']=$productno;
                        $attrm->data($data)->add();
                    }
                    $le=count($ph);
                    $imgm=D('Common/Img');
                    for($i=0;$i<$le;$i++){
                        $data1['img']=$ph[$i];
                        $data1['productno']=$productno;
                        $imgm->data($data1)->add();

                        }
                    $introduce=D('Common/Prointroduce');
                    $data2['introduce']=$posts['introduce'];
                    $data2['productno']=$productno;
                    $introduce->data($data2)->add();
                    $this->redirect('Product/productlist',array(),0,'成功');
                    }

                }
            }
            
           
        }


    }
    public function  producttype(){
        $r=I('get.r');
        if(empty($r)){
        $model=D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
        $count =$model->field()->count();
        //  $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page = new \Org\Util\Page($count,10);

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $prt = $model
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('prt',$prt);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
        }elseif($r=='sousuo') {
            $model = D('Common/Cmd');
            $name = I('post.name');
            if (!empty($name)) {
//            var_dump($name);
                $where['cmd_name'] = array('like', array("%$name", "%$name%", "$name%"));
                $prt = $model->where($where)->field()->select();
//            var_dump($prt);
                $this->assign('prt', $prt);
                $this->display('producttype2');
            }else{
                $this->redirect('Product/producttype');
            }
        }
    }
    //添加类型
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
    //删除类型
    public function deletetype(){
        $id=I('get.id');
        $model=D('Common/Cmd');
        $where['cmd_id']=$id;
        $where['cmd_fid']=$id;
        $where['_logic'] = 'OR';
        //var_dump($where);exit();
        $result=$model->where($where)->delete();
        //var_dump($result);exit;
        if($result=="false" || $result==0){
            $this->redirect('Product/producttype',array(),'1','删除失败');
        }else{
            $this->redirect('Product/producttype',array(),'0','删除成功');
        }

    }

    //添加属性
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
    //上架产品
    public function shelvespro(){
        $prono=I('post.checkbox');
        $where['cmdinfo_no']=array('in',$prono);
        $model=D('Common/Cmdinfo');
        $data['cmdinfo_status']=1;
        $posts=$model->where($where)->save($data);
        if($posts==false){
            echo "<script> alert('上架失败');</script>";
            $this->redirect('Product/productlist',array(),0,"上架失败");
        }else{
            $this->redirect('Product/productlist',array(),0,"上架成功");
        }
    }
    //批量删除产品
    public function deleteall(){
        $prono=I('post.id');
        $prono=explode(',',$prono);
        $where['cmdinfo_no']=array('in',$prono);
        $model=D('Common/Cmdinfo');
        $data=$model->where($where)->delete();//删除基本信息
        if($data==0 || $data=='false'){
            $dat['error']="删除失败";
        }else{
        $imgm=D('Common/Img');
        $where1['productno']=array('in',$prono);
        $img2=$imgm->where($where1)->field()->select();
        foreach ($img2 as $value){
            $path=RECOMMEND.$value['img'];
            unlink($path);
        }
        $data1=$imgm->where($where1)->delete();//删除图片表中的记录
        $introduce=D('Common/Prointroduce');
        $data2=$introduce->where($where1)->delete();//删除介绍
        $attrm=D('Common/Proattr');
        $data3=$attrm->where($where1)->delete();//删除属性
        $dat['success']="删除成功";
        }
        $dat=json_encode($dat,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($dat,'JSON');
    }
    //修改产品信息
    public function updatepro(){
        $request=I('get.r');


        if(empty($request)) {
            $no = I('get.no');
            $model = D('Common/Cmd');       //$model=new \Common\Model\CmdModel;
            $prt = $model->field()->select();
            $this->assign('prt', $prt);
            //查询商家
            $sj = D('Common/Shangjia');
            $shj = $sj->field()->select();
            $this->assign('shj', $shj);


            $cmdinfo = D('Common/Cmdinfo');
            $where['cmdinfo_no'] = $no;
            $list = $cmdinfo->join('__CMD__ ON __CMDINFO__.cmd_id=__CMD__.cmd_id')
                ->join('__SHANGJIA__ ON __CMDINFO__.cmdinfo_sjno=__SHANGJIA__.id')
                ->join('__PROINTRODUCE__ ON __CMDINFO__.cmdinfo_no=__PROINTRODUCE__.productno')
                ->where($where)
                ->select();

            $where2['typeid'] = $list[0]['cmd_id'];
            $model = D('Common/Attr');
            $aty = $model->where($where2)->field()->select();
            $this->assign('aty', $aty);

            $attr = D('Common/Proattr');
            $where1['productno'] = $no;
            $att = $attr->where($where1)->field()->select();
            $this->assign('att', $att);

            //var_dump($att);
            // var_dump($list);exit;
            $this->assign('list', $list);
            $this->display();
        }elseif($request=='add'){
            $posts=I('post.');
            $cmdinfo['cmdinfo_name']=$posts['cmdinfo_name'];
            $cmdinfo['cmd_id']=$posts['cmd_id'];
            $cmdinfo['cmdinfo_sjno']=$posts['cmdinfo_sjno'];
            $cmdinfo['cmdinfo_br']=$posts['cmdinfo_br'];
            $cmdinfo['cmdinfo_price']=$posts['cmdinfo_price'];
            $cmdin1=D('Common/Cmdinfo');
            $wher1['cmdinfo_no']=$posts['no'];
            $cmdin1->where($wher1)->save($cmdinfo);
            
            $introduce=D('Common/Prointroduce');
            $data2['introduce']=$posts['introduce'];
            $introduce->where($wher1)->save($data2);
            
            $this->redirect('Product/productlist',array(),0,'成功');
        }
    }
    
    //产品属性
    public function proattr(){
        $no=I('get.no');
        $this->assign('no',$no);
        $attrm=D('Common/Proattr');
        $request=I('get.r');
        if(empty($request)) {

           $att= $attrm->field('a.id,a.productno,a.attr_name,a.attr_price,a.attr_kucun,b.name as aname')
                ->table('lhl_proattr a,lhl_attr b')
               ->where("a.attr=b.id AND a.productno = '$no'")
                ->select();
            $this->assign('att',$att);
            $this->display();

        }elseif($request=='add'){
            //echo $no;
            $m2=D('Common/Cmdinfo');
            $at= $m2->field('a.cmdinfo_no,b.typeid,b.name,b.id')
                ->table('lhl_cmdinfo a,lhl_attr b')
                ->where("a.cmd_id=b.typeid AND a.cmdinfo_no = '$no'")
                ->select();
//            var_dump($at);
            $this->assign('at',$at);
            $this->display('proattradd');
        }elseif ($request=update){
            $id=I('get.id');
            $m2=D('Common/Cmdinfo');
            $at= $m2->field('a.cmdinfo_no,b.typeid,b.name,b.id')
                ->table('lhl_cmdinfo a,lhl_attr b')
                ->where("a.cmd_id=b.typeid AND a.cmdinfo_no = '$no'")
                ->select();
//            var_dump($at);
            $this->assign('at',$at);
            $m3=D('Common/proattr');
            $whe['id']=$id;
            $dat1=$m3->where($whe)->select();
            //var_dump($da1);exit;
            $this->assign('d2',$dat1);
            $this->display('proattrupdate');


        }elseif($request=='delet') {
            $id = I('get.id');
            $where1['id'] = $id;

           $shanchu = $attrm->where($where1)->delete();
            if ($shanchu == 'false' || $shanchu == 0) {
              echo "失败";
            } else {

                $this->redirect('Product/proattr',array('no'=>$no),0,'成功');
            }
        }
    }
    public function addproattr(){
        $request=I('get.r');
        echo $request;
        $model=D('Common/Proattr');
        if($request=='add'){
            $data=I('post.');
            $l=count($data['attr']);
            for($i=0;$i<$l;$i++){
                $data1['productno']=$data['productno'];
                $data1['attr']=$data['attr'][$i];
                $data1['attr_name']=$data['attr_name'][$i];
                $data1['attr_price']=$data['attr_price'][$i];
                $data1['attr_kucun']=$data['attr_kucun'][$i];
                $ad=$model->data($data1)->add();

            }
            $no=$data['productno'];
            $this->redirect('Product/proattr',array('no'=>$no),0,'添加成功');
            //var_dump($data1);
        }elseif($request=='update'){
            $posts=I('post.');
            print_r($posts);
            $no=$posts['productno'];
            $w1['id']=$posts['id'];
            $w2['attr']=$posts['attr'];
            $w2['attr_name']=$posts['attr_name'];
            $w2['attr_price']=$posts['attr_price'];
            $w2['attr_kucun']=$posts['attr_kucun'];
            $mo=D('Common/Proattr');
            $mo->where($w1)->data($w2)->save();
            $this->redirect('Product/proattr',array('no'=>$no),0,'添加成功');
        }

    }
  //产品搜索
    public function prosousuo(){
        $ss=I('get.ss');
        if($ss=="no"){
            $cmdinfo=D('Common/Cmdinfo');
            $posts['cmdinfo_no']=I('post.cmdinfo_no');
            //var_dump($posts);exit;
            $list = $cmdinfo->join('__CMD__ ON __CMDINFO__.cmd_id=__CMD__.cmd_id')
                ->join('__SHANGJIA__ ON __CMDINFO__.cmdinfo_sjno=__SHANGJIA__.id')
                ->where($posts)
                ->select();
            $this->assign('list',$list);
            $this->display('productlist');
        }elseif ($ss=="name"){
            $posts['cmdinfo_name']=I('post.cmdinfo_name');
            $cmdinfo=D('Common/Cmdinfo');
            $w=$posts['cmdinfo_name'];
            $where['cmdinfo_name']=array('like',array("$w%","%$w%","%$w"));
            //var_dump($posts);exit;
            $list = $cmdinfo->join('__CMD__ ON __CMDINFO__.cmd_id=__CMD__.cmd_id')
                ->join('__SHANGJIA__ ON __CMDINFO__.cmdinfo_sjno=__SHANGJIA__.id')
                ->where($where)
                ->select();
//            var_dump($list);exit;
            $this->assign('list',$list);
            $this->display('productlist');
        }

    }
    //返回产品类型属性
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