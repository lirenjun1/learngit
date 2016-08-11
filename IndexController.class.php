<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){

     $this->display();
    }
    public function main(){
       echo "你好";
        $this->display('main');
    }
}