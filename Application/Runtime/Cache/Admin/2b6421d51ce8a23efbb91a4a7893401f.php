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
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="">管理员</a></li>
        <li class="active">添加管理员</li>
    </ul>

    <div class="page-header clearfix">
        <h3>管理员</h3>
        <ul class="nav nav-tabs">    
            <li>
                <a href="<?php echo U('Admin/adminList');?>">管理员列表</a>
            </li>
            <li class="active">
                <a href="<?php echo U('Admin/addAdmin');?>">添加管理员</a>
            </li>
        </ul>
    </div>

    <div class="content-box-content">
        <form action="<?php echo U('Admin/addAdmin');?>" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="account" class="col-sm-3 control-label">账　　号：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="account" name="account">
                </div>
            </div>   
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">密　　码：</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" id="password" name="password">
                </div>
            </div> 
            <div class="form-group">
                <label for="re_password" class="col-sm-3 control-label">确认密码：</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" id="re_password" name="re_password">
                </div>
            </div> 
            <div class="form-group">
                <label class="col-sm-3 control-label">组　　别：</label>
                <div class="col-sm-9">
                    <select name="group_id" class="form-control input-sm">
                        <option value="0">--请选择组别--</option>
                        <?php if(is_array($group_list)): $i = 0; $__LIST__ = $group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?><option value="<?php echo ($group['group_id']); ?>"><?php echo ($group['group_name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
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

</body>
</html>