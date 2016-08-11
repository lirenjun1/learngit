<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>后台登陆</title>
    <link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/toocms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/leyoou/leyoou/Public/Admin/css/login.css" type="text/css" media="screen" />
    <script type="text/javascript" src="/leyoou/leyoou/Public/Common/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/ajax-operate.js"></script>
    <script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/bootstrap.min.js"></script>
</head>
<body>
<div class="login-wrapper">

    <div class="col-md-4 col-md-offset-4 logo-page"> 

    </div>

    <div class="container" style="margin-top:5%">
        <div class="row">
        <div class="col-md-4 col-md-offset-4 box">          
            <div class="content-wrap">
                <h6>后台登陆</h6>
                <p class="text-danger bg-danger text-alert  displaynone" id="login-error">账户或密码错误，请重新登录！</p>
                <form method="post" action="<?php echo U('Index/index');?>" class="form">
                    <div class="form-group col-lg-12" id="account-form" >
                        <div class="input-group input-group-lg col-lg-12">
                            <span class="input-group-addon">
                                <span aria-hidden="true" class="icon glyphicon glyphicon-user"></span>
                            </span>
                            <input class="form-control" name="account" id="account" type="text" placeholder="账号">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <div class="input-group input-group-lg col-lg-12">
                            <span class="input-group-addon">
                                <span aria-hidden="true" class="icon glyphicon glyphicon-lock"></span>
                            </span>
                            <input class="form-control" name="password" type="password" placeholder="密码">
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="verifyDiv">
                        <div class="input-group input-group-lg col-lg-6" style="float:left; margin-right:10px;">
                            <span class="input-group-addon">
                                <span aria-hidden="true" class="icon glyphicon glyphicon-random"></span>
                            </span>
                            <input class="form-control text-input" name="verify" type="text" placeholder="验证码">
                        </div>
                        <div class="fl">
                            <img src="<?php echo U('Manager/verify');?>" id="verify_img" width="145" height="45" onclick="change_verify()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 login-btn">
                            <input type="hidden" name="errorNum" id="errorNum" value="0" />
                            <p>
                                <input type="button" class="btn btn-primary btn-lg btn-block submit-btn" value="立刻登录">
                            </p>
                        </div>
                    </div>
                    <div class="notification"></div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    function change_verify(){
        var str = "<?php echo U('Manager/verify');?>";
        document.getElementById('verify_img').src=str;
    }
    $(function(){
        $('#account').focus();
        $('.submit-btn').click(function(){
            ajaxLogin('<?php echo U("Manager/doLogin");?>','<?php echo U("Manager/verify");?>');
        });
    });
</script>
</html>