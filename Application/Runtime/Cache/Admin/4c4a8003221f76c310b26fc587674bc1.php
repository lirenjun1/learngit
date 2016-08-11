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
    </ul>
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
    <div class="page-header clearfix">
        <h3>脑暴奖金审核发放</h3>
    </div>
    <div class="content-box-content">
        <div>待审核发放</div>
        <form class="batch-form">
            <table class="table table-striped table-framed table-hover">
                <thead>
                <tr>
                    <th width="5%">项目编号</th>
                    <th width="8%">发布时间</th>
                    <th width="5%">发布人ID</th>
                    <th width="16%">项目名称</th>
                    <th width="6%">金额（元）</th>
                    <th width="6%">参与状态</th>
                    <th width="5%">订单状态</th>
                    <th width="12%" >&nbsp;&nbsp;操作</th>
                </tr>
                </thead>
                <!--标题 end-->

                <!--内容 start-->
                <tbody class="tbody">
                <?php if(empty($task_list)): ?><tr><td colspan="10"><span style="font-size:14px;">暂无数据</span></td></tr><?php endif; ?>
                <?php if(is_array($task_list)): $i = 0; $__LIST__ = $task_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($i % 2 );++$i; if($task['tstatus'] == '待发奖'): ?><tr>
                            <td><?php echo ($task['t_id']); ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$task['ctime'])); ?></td>
                            <td><?php echo ($task['m_id']); ?></td>
                            <td><?php echo ($task['name']); ?></td>
                            <td><?php echo ($task['money']); ?></td>
                            <?php if($task['max_player_num'] <= 8): ?><td><?php echo ($task['players']); ?>/<?php echo ($task['max_player_num']); ?></td><?php endif; ?>
                            <?php if($task['max_player_num'] > 8): ?><td><?php echo ($task['players']); ?>参加</td><?php endif; ?>

                            <td align="center">
                                <i style="color: red">待发奖</i>
                            </td>

                            <td>
                                <a href="<?php echo U('Task/ddTask',array('t_id'=>$task['t_id']));?>">查看详情&nbsp;</a>
                            </td>
                        </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </form>
    </div>
    <div style="border-bottom: gold 2px solid;width: 100%;margin: 50px 0px"></div>
    <div class="content-box-content">
        <div>已结束</div>
        <form class="batch-form">
            <table class="table table-striped table-framed table-hover">
                <thead>
                <tr>
                    <th width="5%">项目编号</th>
                    <th width="8%">发布时间</th>
                    <th width="5%">发布人ID</th>
                    <th width="16%">项目名称</th>
                    <th width="6%">金额（元）</th>
                    <th width="6%">参与状态</th>
                    <th width="5%">订单状态</th>
                    <th width="12%" >&nbsp;&nbsp;操作</th>
                </tr>
                </thead>
                <!--标题 end-->

                <!--内容 start-->
                <tbody class="tbody">
                <?php if(empty($task_list)): ?><tr><td colspan="10"><span style="font-size:14px;">暂无数据</span></td></tr><?php endif; ?>
                <?php if(is_array($task_list)): $i = 0; $__LIST__ = $task_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($i % 2 );++$i; if($task['tstatus'] == '已结束'): ?><tr>
                            <td><?php echo ($task['t_id']); ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$task['ctime'])); ?></td>
                            <td><?php echo ($task['m_id']); ?></td>
                            <td><?php echo ($task['name']); ?></td>
                            <td><?php echo ($task['money']); ?></td>
                            <?php if($task['max_player_num'] <= 8): ?><td><?php echo ($task['players']); ?>/<?php echo ($task['max_player_num']); ?></td><?php endif; ?>
                            <?php if($task['max_player_num'] > 8): ?><td><?php echo ($task['players']); ?>参加</td><?php endif; ?>
                            <td align="center">
                                <i style="color: red">已发放完成</i>
                            </td>

                            <td>
                                <a href="<?php echo U('Task/ddTask',array('t_id'=>$task['t_id']));?>">查看详情&nbsp;</a>
                            </td>
                        </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
</body>
</html>