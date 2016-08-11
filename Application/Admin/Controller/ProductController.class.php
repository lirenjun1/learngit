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
}