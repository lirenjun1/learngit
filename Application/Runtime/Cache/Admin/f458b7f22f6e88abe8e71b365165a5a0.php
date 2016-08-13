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

<div id="main-content" class="content">
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="">管理员</a></li>
        <li class="active">管理员组列表</li>
    </ul>

    <div class="page-header clearfix">
        <h3>管理员</h3>
        <ul class="nav nav-tabs">    
            <li class="active">
                <a href="<?php echo U('AdminGroup/groupList');?>">管理员组列表</a>
            </li>
            <li>
                <a href="<?php echo U('AdminGroup/addGroup');?>">添加管理员组</a>
            </li>
        </ul>
    </div>
    <div class="content-box-content">
        <table class="table table-striped table-framed table-hover">
            <thead>
            <tr>
                <th  width="4%">编号</th>
                <th width="10%">添加时间</th>
                <th>组名</th>
                <th>管理员个数</th>
                <th>授权</th>
                <th width="6%">操作</th>
            </tr>
            </thead>
            <tbody class="tbody">
            <?php if(empty($group_list)): ?><tr><td colspan="10"><span style="font-size:14px;">暂无数据</span></td></tr><?php endif; ?>
            <?php if(is_array($group_list)): $i = 0; $__LIST__ = $group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($group['group_id']); ?></td>
                    <td><?php echo (date('Y-m-d',$group['ctime'])); ?></td>
                    <td><?php echo ($group['group_name']); ?></td>
                    <td><?php echo ($group['admin_count']); ?></td>
                    <td><?php echo ($group['permission']); if($group['flag'] == 'more'): ?>&nbsp;...<?php endif; ?></td>
                    <td>
                        <a href="<?php echo U('AdminGroup/editGroup',array('group_id'=>$group['group_id']));?>" title="编辑" class="modify">
                            <span class="icon glyphicon glyphicon-pencil"></span>
                        </a>&nbsp;
                        <a href="#" title="删除" class="delete-delete">
                            <span class="icon glyphicon glyphicon-remove"></span>
                        </a><input type="hidden" value="<?php echo U('AdminGroup/deleteGroup',array('group_id'=>$group['group_id']));?>">
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>

            <tfoot>
            <!--<tr>
                <td colspan="20">
                    <div class="bulk-actions align-left">
                    </div>
                    <div class="pagination">

                    </div>
                    <div class="clear"></div>
                </td>
            </tr>-->
            </tfoot>
        </table>
    </div>
</div>

</body>
</html>