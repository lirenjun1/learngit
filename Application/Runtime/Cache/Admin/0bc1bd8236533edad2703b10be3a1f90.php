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
<!--主页面-->
<div id="main-content" class="content">
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="">管理员</a></li>
        <li class="active">添加管理员组</li>
    </ul>

    <div class="page-header clearfix">
        <h3>管理员</h3>
        <ul class="nav nav-tabs">    
            <li>
                <a href="<?php echo U('AdminGroup/groupList');?>">管理员组列表</a>
            </li>
            <li class="active">
                <a href="<?php echo U('AdminGroup/addGroup');?>">添加管理员组</a>
            </li>
        </ul>
    </div>
    <div class="content-box-content">

        <form action="<?php echo U('AdminGroup/addGroup');?>" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label">组名：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control group-name" id="group_name" name="group_name">
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label">权限：</label>
                <div class="col-sm-9">
                    <?php if(is_array($action_list)): $i = 0; $__LIST__ = $action_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$action): $mod = ($i % 2 );++$i;?><div class="fl" style="width:180px;padding:5px;">
                            <input type="checkbox" value="<?php echo ($action['action_id']); ?>" name="permission[]"/>&nbsp;<?php echo ($action['action_name']); ?>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                  <button type="submit" class="btn btn-default btn-primary">确认添加</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--主页面 end-->
</body>
</html>