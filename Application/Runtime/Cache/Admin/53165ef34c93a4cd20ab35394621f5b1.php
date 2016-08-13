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
                <label class="sr-only" for="con_mid">请输入产品编号</label>
                <input class="earch-input form-control" id="con_mid" type="text" name="con_mid" placeholder="请输入产品编号"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>
        </form>
        <form action="<?php echo U('Member/memList');?>" method="post" class="search-form form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="con_maccount">请输入产品名</label>
                <input class="earch-input form-control" id="con_maccount" type="text" name="con_maccount" placeholder="请输入产品名成"/>　
            </div>
            <button type="submit" class="btn derive">查询</button>
        </form>

        <a href="<?php echo U('Admin/Product/typeadd');?>" class="btn btn-default btn-lg " role="button" style="float: right; color: #000000">添加产品类型</a>
    </div>
    <div class="content-box-table content-box-content">
        <form action="<?php echo U('Member/deleteMem');?>" method="post" class="batch-form">
            <table class="table table-striped table-framed table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th >产品编号</th>
                    <th >是否上架</th>
                    <th>产品名称</th>
                    <th >产品图片</th>
                    <th >产品介绍</th>
                    <th >价格</th>
                    <th >所属分类</th>
                    <th >产品属性</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody class="tbody">

                <tr>
                    <td>
                        <input type="checkbox"  value="option1">
                    </td>
                    <td >产品编号</td>
                    <td >未上架</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox"  value="option1">
                    </td>
                    <td >产品编号</td>
                    <td >未上架</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                    <td >产品编号</td>
                </tr>
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
                            <button type="button" class="btn btn-primary">上架选中的</button>
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