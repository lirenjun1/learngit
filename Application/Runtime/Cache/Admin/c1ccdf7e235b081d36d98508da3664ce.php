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
<style>
    .form-inline{
        display: inline-block;
        width: 300px;
    }
</style>
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
    <div class="search-content">
        <form action="<?php echo U('Member/memList');?>" method="post" class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_mid">请输入用户编号</label>
                <input class="earch-input form-control" id="con_mid" type="text" name="con_mid" placeholder="请输入用户编号"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>
        </form>
        <form action="<?php echo U('Member/memList');?>" method="post" class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_maccount">请输入用户名</label>
                <input class="earch-input form-control" id="con_maccount" type="text" name="con_maccount" placeholder="请输入用户名"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>
        </form>
        <form action="<?php echo U('Member/memList');?>" method="post" class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_maccount">请输入邮箱</label>
                <input class="earch-input form-control" id="con_memail" type="text" name="con_memail" placeholder="请输入邮箱"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>
        </form>
    </div>
    <div class="content-box-table content-box-content">
        <form action="<?php echo U('Member/deleteMem');?>" method="post" class="batch-form">
            <table class="table table-striped table-framed table-hover">
                <thead>
                <tr>
                    <th width="3%">用户编号</th>
                    <th width="5%">注册时间</th>
                    <th width="5%">用户名</th>
                    <th width="5%">邮箱</th>
                    <th width="8%">是否是商家</th>
                    <th width="8%">身价（元/小时）</th>
                    <th width="5%">是否推荐</th>
                    <th width="8%">是否实名认证</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody class="tbody">
                <?php if(empty($data)): ?><tr>
                        <td colspan="20"><span style="font-size:14px;">暂无数据</span></td>
                    </tr><?php endif; ?>
                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><tr>
                        <td><input type="checkbox" class="delCheck" value="<?php echo ($member['m_id']); ?>"/>&nbsp;&nbsp;<?php echo ($da['login_id']); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$da['r_time'])); ?></td>
                        <td><?php echo ((isset($da['user_account']) && ($da['user_account'] !== ""))?($da['user_account']):"未填写用户名"); ?></td>
                        <td><?php echo ($da['login_email ']); ?></td>
                        <td><?php echo ($da['region']); ?></td>
                        <td><?php echo ($da['money']); ?></td>
                        <td>
                            <?php if($member['is_recommend'] == 1): ?><i style="color: green">是</i>
                                <?php else: ?>
                                否<?php endif; ?>
                        </td>
                        <td>
                            <?php if($member['status'] == 0): ?><i style="color: grey">未实名</i>
                                <?php elseif($member['status'] == 1): ?>
                                <i style="color: green">待审核</i>
                                <?php elseif($member['status'] == 2): ?>
                                <i style="color: #B06930">已实名</i>
                                <?php elseif($member['status'] == 3): ?>
                                <i style="color: red">审核不通过</i><?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo U('Member/editmem',array('m_id'=>$member['m_id']));?>">更多</a>
                            &nbsp;&nbsp;
                            <a href="<?php echo U('Member/deleteMem',array('m_id'=>$member['m_id']));?>">删除</a>
                            <?php if($member['is_recommend'] != 1): ?>&nbsp;&nbsp;
                                <a href="<?php echo U('Member/isRecommend',array('m_id'=>$member['m_id'],'isReco'=>1));?>">推荐</a>
                                <?php else: ?>
                                &nbsp;&nbsp;
                                <a href="<?php echo U('Member/isRecommend',array('m_id'=>$member['m_id'],'isReco'=>0));?>">取消推荐</a><?php endif; ?>
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
        //批量删除
        $('.delete-batch').click(function(){
            if(confirm('确定要执行批量删除操作吗？')){
                var ids = [];
                //获取选中的信息
                $(".delCheck").each(function(){
                    if($(this).is(":checked")){
                        ids.push($(this).val());
                    }
                })
                id = ids.join(',');
                if(id != ''){
                    $.ajax(
                            {
                                'url':'<?php echo U("Member/deleteMemberAll");?>',
                                'type':'post',
                                'data':{
                                    id:id,
                                },
                                success: function(data){
                                    data = JSON.parse(data);
                                    if(data.success){
                                        alert(data.success);
                                        $(".delCheck").each(function(){
                                            $(this).attr("checked",false);
                                        })
                                        location.reload();
                                    }else{
                                        alert(data.error);
                                    }
                                }
                            }
                    )
                }
            }
        });
    });
</script>
</body>
</html>