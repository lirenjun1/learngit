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
        <li class="active">管理员列表</li>
    </ul>

    <div class="page-header clearfix">
        <h3>管理员</h3>
        <ul class="nav nav-tabs">    
            <li class="active">
                <a href="<?php echo U('Admin/adminList');?>">管理员列表</a>
            </li>
            <li>
                <a href="<?php echo U('Admin/addAdmin');?>">添加管理员</a>
            </li>
        </ul>
    </div>


    <div class="content-box-content">
        <table class="table table-striped table-framed table-hover">
            <thead>
            <tr>
                <th width="4%">编号</th>
                <th width="10%">添加时间</th>
                <th>账号</th>
                <th>组别</th>
                <th>最后登录时间</th>
                <th>最后登录IP</th>
                <th width="6%">操作</th>
            </tr>
            </thead>
            <!--标题 end-->

            <!--内容 start-->
            <tbody class="tbody">
            <?php if(empty($admin_list)): ?><tr><td colspan="10"><span style="font-size:14px;">暂无数据</span></td></tr><?php endif; ?>
            <?php if(is_array($admin_list)): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$admin): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($admin['admin_id']); ?></td>
                    <td><?php echo (date('Y-m-d',$admin['admin_ctime'])); ?></td>
                    <td><?php echo ($admin['admin_account']); ?></td>
                    <td>
                        <?php if($admin['admin_id'] == 1): ?>超级管理员
                        <?php else: ?>
                            <?php echo ($admin['admin_group_name']); endif; ?>
                    </td>
                    <td><?php echo (date('Y-m-d H:i:s',$admin['admin_login_time'])); ?></td>
                    <td><?php echo ($admin['admin_login_ip']); ?></td>
                    <td>
                        <?php if($admin['admin_id'] == 1): ?><a href="<?php echo U('Admin/editAdmin',array('a_id'=>$admin['admin_id']));?>" title="编辑" class="modify">
                                <span class="icon glyphicon glyphicon-pencil"></span>
                            </a>&nbsp;
                            <a href="#" title="删除" class="delete-delete">
                               <span class="icon glyphicon glyphicon-remove"></span>
                            </a><input type="hidden" value="<?php echo U('Admin/deleteAdmin',array('admin_id'=>$admin['admin_id']));?>"><?php endif; ?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>

            <tfoot>

            </tfoot>
        </table>
    </div>
</div>
</body>
</html>