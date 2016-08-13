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
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/learngit/Public/Admin/bianyi/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>

<div id="main-content" class="content">
    <ul class="breadcrumb">
        <li><a href="#">首页</a></li>
    </ul>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">添加产品类型</a></li>
    </ul>
    <form action="<?php echo U('Product/typeadd',array('r'=>'add'));?>" class="form-horizontal" method="post" >
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="form-group">
                    <label for="inputText3" class="col-sm-2 control-label">类型名称</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="inputText3" placeholder="类型名称">
                        <p class="help-block" style="color: red">必填类型名称</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-2 control-label">类型logo</label>
                    <div class="col-sm-10">
                        <input type="file"  id="exampleInputFile">
                       
                    </div>
                </div>
                <div class="form-group">
                    <label for="select"  class="col-sm-2 control-label">上级类型</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="parentid" id="select">
                            <option value="0">请选择</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
            </div>
    </form>

</div>