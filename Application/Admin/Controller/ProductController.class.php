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
        $this->display();
    }
    public function productadd(){
        $this->display();
    }
    public function  producttype(){
        $this->display();
    }
    public function typeadd(){
        $request=I('get.r');
        if(empty($request)){
            $this->display();
        }elseif($request=='add'){
            $posts=I('post.');
            var_dump( $posts);
        }

    }
    public function productattr(){
        $this->display();

    }
    public function attradd(){
        $this->display();
    }
}