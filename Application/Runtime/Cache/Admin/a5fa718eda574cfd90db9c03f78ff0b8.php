<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
<link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/toocms.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/invalid.css" type="text/css" media="screen" />
<script type="text/javascript" src="/leyoou/leyoou/Public/Common/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/common.js"></script>
</head>
<body>
<script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/ajax-operate.js"></script>
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
    </ul>
    <div class="tab-content">
        <form class="form-horizontal">
        <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="form-group">
                    <label for="inputText3" class="col-sm-2 control-label">产品名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText3" placeholder="产品名称">
                    </div>
                </div>
                <div class="form-group has-success has-feedback">
                    <label for="inputText4" class="col-sm-2 control-label">商品货号</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText4" placeholder="商品货号">
                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                        <span id="inputGroupSuccess4Status" class="sr-only">(success)</span>
                    </div>

                </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="profile">.ddddddfff..</div>
        <div role="tabpanel" class="tab-pane fade" id="messages">..ffgggg.</div>
        <div role="tabpanel" class="tab-pane fade" id="settings">..hhhhhh.</div>
        <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">保存</button>
        </div>
    </div>
        </form>
    </div>

</div>