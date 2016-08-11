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
<!--主页面-->
<div id="main-content" class="content">
    <ul class="breadcrumb">
        <li><a href="#">首页</a></li>
        <li><a href="#">项目管理</a></li>
        <li class="active">项目列表</li>
    </ul>

    <div class="page-header clearfix">
        <h3>项目管理</h3>
        <ul class="nav nav-tabs">    
            <li class="active">
                <a href="<?php echo U('Task/taskList');?>">项目列表</a>
            </li>
        </ul>
    </div>
    <!--提示 start-->
<div class="alert alert-success alert-dismissible notification success" role="alert" style="display:none">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <div></div>
</div>
<div class="alert alert-danger alert-dismissible notification error n-error" role="alert" style="display:none">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <div></div>
</div>
<!--提示 end-->
    <!--<div class="search-content">
        <form action="<?php echo U('Task/taskList');?>" method="post"class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_tid">请输入项目编号</label>
                <input class="earch-input form-control" id="con_tid" type="text" name="con_tid" placeholder="请输入项目编号"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>　
        </form>
        <form action="<?php echo U('Task/taskList');?>" method="post"class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_tname">请输入项目名称</label>
                <input class="earch-input form-control" id="con_tname" type="text" name="con_tname" placeholder="请输入项目名称"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>　
        </form>
    </div>-->
    <div class="content-box-content">
        <form action="<?php echo U('Task/deleteTask');?>" method="post" class="batch-form">
            <table class="table table-striped table-framed table-hover">
                <thead>
                <tr>
                    <th width="5%">项目编号</th>
                    <th width="8%">发布时间</th>
                    <th width="5%">发布人</th>
                    <th width="6%">项目名称</th>
                    <th width="15%">投稿时间</th>
                    <th width="5%">是否审核通过</th>
                    <th width="12%" >&nbsp;&nbsp;操作</th>
                </tr>
                </thead>
                <!--标题 end-->

                <!--内容 start-->
                <tbody class="tbody">
                <?php if(empty($task_list)): ?><tr><td colspan="10"><span style="font-size:14px;">暂无数据</span></td></tr><?php endif; ?>
                <?php if(is_array($task_list)): $i = 0; $__LIST__ = $task_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($i % 2 );++$i;?><tr>
                        <td><input type="checkbox" name="t_id[]" value="<?php echo ($task['t_id']); ?>"/>&nbsp;&nbsp;<?php echo ($task['t_id']); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$task['ctime'])); ?></td>
                        <td><?php echo ($task['m_account']); ?></td>
                        <td><?php echo ($task['name']); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$task['bid_start_time'])); ?>-<?php echo (date('Y-m-d H:i:s',$task['bid_end_time'])); ?></td>
                        <td align="center">
                            <?php if($task['status'] == 0): ?><i style="color: grey">审核中</i>
                                <?php elseif($task['status'] == 1): ?>
                                <i style="color: green;">通过</i>
                                <?php elseif($task['status'] == 2): ?>
                                <i style="color: red">不通过</i>
                                <?php elseif($task['status'] == 8): ?>
                                <i style="color: grey">未付款</i>
                                <?php elseif($task['status'] == 3): ?>
                                <i style="color: grey">已结束</i>
                                <?php elseif($task['status'] == 7): ?>
                                <i style="color: grey">已退款</i><?php endif; ?>
                        </td>
                        <td>
                            <?php if($task['status'] != 8): if($task['status'] == 0): ?><a href="<?php echo U('Task/detailTask',array('t_id'=>$task['t_id']));?>">编辑审核&nbsp;</a>
                                    <a href="<?php echo U('Task/deleteTask',array('t_id'=>$task['t_id']));?>">&nbsp;删除</a><?php endif; endif; ?>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="20">
                        <div class="bulk-actions fl">
                            <input type="button" class="btn delete-batch" value="批量删除">
                        </div>
                        <div class="fr">
                            <?php echo ($page); ?>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //排序
        $('.sort_order').blur(function(){
            var id = $(this).next('input').val();
            var sort = $(this).val();
            ajaxEditSort('<?php echo U("Task/editSort");?>',id,sort);
        });
        //批量删除
        $('.delete-batch').click(function(){
            if(confirm('确定要执行批量删除操作吗？')){
                var str = '';
                $(".tbody input[type='checkbox']:checked").each(function(key,obj){
                    str = str + obj.value;
                });
                if(str == ''){
                    showNotification('error','您未选择操作对象');
                }else{
                    $('.batch-form').submit();
                    //'<?php echo U('Task/deleteTask',array('t_id'=>$task['t_id']));?>';
                }
            }
        });
    });
</script>
</body>
</html>