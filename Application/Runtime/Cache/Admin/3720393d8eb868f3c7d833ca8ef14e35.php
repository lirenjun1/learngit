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
<!--日期插件-->
<link rel="stylesheet" href="/leyoou/leyoou/Public/Home/DatePicker/lhgcalendar.css" type="text/css"/>
<script src="/leyoou/leyoou/Public/Common/DatePicker/lhgcore.js"></script>
<script src="/leyoou/leyoou/Public/Common/DatePicker/lhgcalendar.js"></script>
<style>
    .form-control {
        width: 100%;
    }
    .task-tag-list{
        width: 600px;
    }
    .task-tag{
        background: #ccc;
        padding: 6px 24px 6px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px 5px;
        word-break:break-all;
        position: relative;
    }
    .task-tag a{
        cursor: pointer;
        position: absolute;
        right: 4px;
        top: 4px;
    }
    .mh_date{
        width: 160px;
        text-align: center;
        padding: 5px;
        font-size: 14px;
    }
    .swfupload{
        width: 15%;
    }
    .form-control{
        width: 60%;
    }
</style>
<!--主页面-->
<div id="main-content" class="content">
    <div class="content-box">

        <ul class="breadcrumb">
            <li><a href="#">首页</a></li>
            <li><a href="#">项目管理</a></li>
            <li class="active">发布项目</li>
        </ul>
        <div class="page-header clearfix">
            <h3>发布项目</h3>
            <ul class="nav nav-tabs">    
                <li>
                    <a href="<?php echo U('Task/taskList');?>">项目列表</a>
                </li>
                <li class="active">
                    <a href="#">发布项目</a>
                </li>
            </ul>
        </div>

        <!--表格内容-->
        <div class="content-box-content">
            <!--表单 start-->
            <div class="form-group">
                <label  class="col-sm-2 control-label"><em class="prompt-red">*</em>广告主：</label>
                <div class="col-sm-10">
                    <select type="text" class="form-control" id="m_id" name="m_id">
                        <option value="0">请选择广告主</option>
                        <?php if(is_array($member)): $i = 0; $__LIST__ = $member;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$member): $mod = ($i % 2 );++$i;?><option value="<?php echo ($member['m_id']); ?>"><?php echo ($member['m_email']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><em class="prompt-red">*</em>主题：</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="task-name" name="task-name" placeholder="给脑暴取一个响亮的主题">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label"><em class="prompt-red">*</em>背景介绍：</label>
                <div class="col-sm-10">
                    <textarea style="height: 100px" class="form-control" id="task-backinfo" name="task-backinfo" placeholder="讲讲你的脑暴背景是什么，背景越详细，脑洞质量越高？"></textarea>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><em class="prompt-red">*</em>奖金：</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="task-money" name="task-money" placeholder="脑暴奖金">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><em class="prompt-red">*</em>人数：</label>
                <select type="text" style="width: 100px;display: inline-block;margin-left: 15px" class="form-control" id="task-player" name="task-player">
                    <option value="8">8</option>
                    <option value="7">7</option>
                    <option value="6">6</option>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                    <option value="2000">无限制</option>
                </select>
                &nbsp;人
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label"><em class="prompt-red">*</em>投稿时间：</label>
                <div class="col-sm-10">
                    <input type="text" class="mh_date" id="bid_start_time" readonly="true" value="<?php echo ($now); ?>">
                    <br>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label"><em class="prompt-red">*</em>投稿时长：</label>
                <div class="col-md-10">
                    <select type="text" style="width: 80px;display: inline-block" class="form-control" id="time_span">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    &nbsp;天
                    <br>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input id="task-picture" type="hidden">
                    <input name="task-picture" id="task-picture-t" type="hidden">
                    <div>
                        <img style="max-height: 250px;width: auto" src="" id="task-logo">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" onclick="addTask()" class="btn btn-default btn-primary">提交脑暴</button>
                </div>
            </div>
            <div style="margin-bottom: 50px;clear: both"></div>
            <!--表单 end-->
        </div>
    </div>
</div>

</body>
</html>
<link href="/leyoou/leyoou/Public/Common/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="/leyoou/leyoou/Public/Admin/js/bootstrap.min.js"></script>
<script src="/leyoou/leyoou/Public/Common/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/leyoou/leyoou/Public/Home/js/plugins/uploadify/jquery.uploadify.min.js"></script>
<script src="/leyoou/leyoou/Public/Common/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>


<link rel="stylesheet" type="text/css" href="/leyoou/leyoou/Public/Home/js/plugins/uploadify/uploadify.css" media="all">
<style type="text/css">
    .swfupload{
        width: 100%;
        height: 100%;
        cursor: pointer;

    }
    .my-button{
        border: 1px solid;
        margin: 20px 0px;
        position: relative;
        margin-bottom: 1em;
        padding: 5px 30px 5px 15px;
        width: 150px;
    }

    .glyphicon.icon-arrow-left,.glyphicon.icon-arrow-right{
        display: inline-block;
        font: normal normal normal 14px/1 Glyphicons Halflings !important;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
    }
    .glyphicon.icon-arrow-left:before{
        content: "\e079";
    }
    .glyphicon.icon-arrow-right:before{
        content: "\e080";
    }
</style>
<script type="text/javascript">
    $(function(){

        $("#bid_start_time").datetimepicker({
            language:"zh-CN",
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            todayBtn: true,
            startDate: "<?php echo ($now); ?>",
            minuteStep: 5,
            showMeridian: true,
        });

        /***************************************************标签功能***************************************************/
        $("#task-lightspot-t").next().click(createTag);
        $("#task-aim-people-t").next().click(createTag);
        $("#task-aim-t").next().click(createTag);
        $("#task-other-require-t").next().click(createTag);
        $("#task-keyword-t").next().click(createTag);
        function createTag(){
            var t = $(this).prev();
            var s = t.val().trim();
            if(s){
                var html = "";
                html += '<p class="task-tag"><a class="glyphicon glyphicon-remove"></a>';
                html += s;
                html +='</p>';
                var tag = $(html);
                tag.hide().children('a.glyphicon').click(function(){
                    $(this).hide();
                    tag.slideUp(300,function(){
                        tag.remove();
                        saveTag(t);
                    })
                });
                t.before(tag).val('');
                tag.slideDown(300);
                saveTag(t);
            }
        }

        function saveTag(t){
            var tags = [];
            t.parent().children("p.task-tag").each(function(){
                tags.push($(this).text());
            });
            t.next().next().val(tags.join(','));
        }
    })

    function addTask(){
        $.ajax({
            type: 'post',
            url: "<?php echo U('Task/addTask');?>",
            data:{
                'm_id':$('#m_id').val(),
                'name':$('#task-name').val(),
                'back_info':$('#task-backinfo').val(),
                'picture':$('#task-picture-t').val(),
                'money':$("#task-money").val(),
                'max_player_num':$("#task-player option:selected").val(),
                'bid_start_time':_getTime($('#bid_start_time').val()),
                'time_span':$('#time_span').val()
            },
            dataType: 'json',
            success: function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    alert(data.success);
                    var url = "<?php echo U('Task/taskList');?>";
                    window.location.href = url;
                }else {
                    alert(data.error);
                }
            },
        })
    }
    function _getTime(str){
        var date = new Date(str);
        var t = date.getTime()/1000;
        if(isNaN(t))return '';
        else return t;
    }
</script>
<script type="text/javascript">
    //上传脑暴图片
    $("#task-picture").uploadify({
        //指定swf文件
        'swf': '/leyoou/leyoou/Public/Home/js/plugins/uploadify/uploadify.swf',
        //后台处理的页面
        'uploader': '<?php echo U("Task/uploadPicture");?>',
        //上传文件的类型  默认为所有文件    'All Files'  ;  '*.*'
        //在浏览窗口底部的文件类型下拉菜单中显示的文本
        'fileTypeDesc': 'Image Files',
        //允许上传的文件后缀
        'fileTypeExts': '*.jpeg; *.jpg; *.png',
        //设置为true将允许多文件上传
        'multi': false,
        'onUploadSuccess':function(file,data){
            data=$.parseJSON(data);
            $("#task-picture-t").val(data.fileurl);
            if(data.success){
                $('#task-logo').attr("src","/leyoou/leyoou/Uploads/"+ data.fileurl).css('height','auto');
            }else{
                alert(data.error);
            }
        }
    });
    $("#task-picture").attr('style','').addClass('my-button').append("<div style='text-align: center'><em class='prompt-red'>*</em>上传脑暴首图</div>");
    $("#task-picture-button").remove();
</script>