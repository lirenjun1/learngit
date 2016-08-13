<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
<link rel="stylesheet" href="/learngit/Public/Admin/css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/learngit/Public/Admin/css/toocms.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/learngit/Public/Admin/css/invalid.css" type="text/css" media="screen" />
<script type="text/javascript" src="/learngit/Public/Common/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/learngit/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/learngit/Public/Admin/js/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="/learngit/Public/Admin/js/common.js"></script>
</head>
<body>
<script type="text/javascript" src="/learngit/Public/Admin/js/ajax-operate.js"></script>
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>

<div id="main-content" class="content">
    <ul class="breadcrumb">
        <li><a href="#">首页</a></li>
    </ul>
<h3>添加产品</h3>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">通用信息</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">详细描述</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">产品属性</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">产品相册</a></li>
        <li role="presentation"><a href="#jieshao" aria-controls="jieshao" role="tab" data-toggle="tab">产品介绍</a></li>
    </ul>
    <form class="form-horizontal">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="form-group">
                    <label for="inputText3" class="col-sm-2 control-label">产品名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText3" placeholder="产品名称">
                        <p class="help-block" style="color: red">必填产品名称</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputText4" class="col-sm-2 control-label">产品货号</label>
                    <div class="col-sm-10 ">
                        <input type="text" class="form-control" id="inputText4" placeholder="产品货号">
                        <p class="help-block" style="color: red">如果为空则会自动生成</p>
                    </div>
                </div>
            <div class="form-group">
                <label for="select" class="col-sm-2 control-label">产品类型</label>
                <div class="col-sm-10">
                    <select class="form-control" id="select">
                        <option>请选择</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="select1" class="col-sm-2 control-label">所属商家</label>
                <div class="col-sm-10">
                    <select class="form-control" id="select1">
                        <option>请选择</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputText5" class="col-sm-2 control-label">产品价格</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputText5" placeholder="保留两位小数">
                    <p class="help-block" style="color: red">保留两位小数</p>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputFile" class="col-sm-2 control-label">产品主图</label>
               <div class="col-sm-10">
                <input type="file"  id="exampleInputFile">
                   <p class="help-block" style="color: red">图片不能为空</p>
            </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="profile">
            <textarea class="form-control" rows="5"></textarea>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="messages">..ffgggg.</div>
        <div role="tabpanel" class="tab-pane fade" id="settings">..hhhhhh.</div>
        <div role="tabpanel" class="tab-pane fade" id="jieshao">
        <div class="form-group">
            <label for="container" class="col-sm-2 control-label">商品介绍:</label> <br>
            <script id="container"style="width: 95%;height: 400px;margin: 20px auto;" name="introduce" type="text/plain">

            </script>
        </div>
        </div>
    </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </div>
        </form>

</div>