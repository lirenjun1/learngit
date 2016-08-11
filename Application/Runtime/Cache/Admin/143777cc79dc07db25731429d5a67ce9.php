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
<!--主页面-->
<div id="main-content" class="content">
    <div class="content-box">

        <ul class="breadcrumb">
            <li><a href="#">首页</a></li>
        </ul>

        <div class="page-header clearfix">
            <h3>4A用户</h3>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#">普通用户添加</a>
                </li>
                <li>
                    <a href="<?php echo U('Online/addOnline');?>">添加4A用户</a>
                </li>
                <!---<li>
                    <a href="<?php echo U('Originality/addOcreative');?>">添加创意人</a>
                </li>-->
            </ul>
        </div>
        <!--表格内容-->
        <div class="content-box-content">
            <!--表单 start-->
            <form action="<?php echo U('Member/addMem');?>" method="post" class="form-horizontal">
                <div class="form-group ">
                    <label for="m_account" class="col-sm-3 control-label"><em class="prompt-red">*</em>用&nbsp;户&nbsp;名：</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="m_account" name="m_account" placeholder="想一个创意的用户名吧">
                    </div>
                </div>
                <div class="form-group ">
                    <label for="m_email" class="col-sm-3 control-label"><em class="prompt-red">*</em>邮&nbsp;&nbsp;&nbsp;箱：</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="m_email" name="m_email" placeholder="12345678@qq.com">
                    </div>
                </div>
                <div class="form-group">
                    <label for="m_password" class="col-sm-3 control-label"><em class="prompt-red">*</em>登录密码：</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="m_password" name="m_password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pay_pwd" class="col-sm-3 control-label"><em class="prompt-red">*</em>支付密码：</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="pay_pwd" name="pay_pwd">
                    </div>
                </div>
                <div class="form-group">
                    <label for="deposit" class="col-sm-3 control-label"><em class="prompt-red">*</em>预设资产（元）：</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="deposit" value="0" name="deposit">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                      <button type="submit" class="btn btn-default btn-primary">确认添加</button>
                    </div>
                </div>
            </form>
            <!--表单 end-->
        </div>
    </div>
</div>
</body>
</html>