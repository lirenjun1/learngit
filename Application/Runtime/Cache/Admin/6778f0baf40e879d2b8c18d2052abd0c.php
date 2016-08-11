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
<script type="text/javascript" src="/leyoou/leyoou/Public/Admin/js/menu.simpla.jquery.js"></script>
    <div id="sidebar" class="sidebar">
        <div class="sidebar-inner">
            <ul class="nav nav-list" id="left-menu">
                <li>
                    <a href="<?php echo U('Index/main');?>" target="main"  class="nav-top-item no-submenu active">
                        <span class="icon glyphicon glyphicon-home"></span>
                        <span class="hidden-minibar">首页</span>
                    </a>
                </li>
                <!--<li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-tasks"></span>
                        <span class="hidden-minibar">4A订单</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Order/orderlist');?>" target="main">订单信息</a>
                        </li>
                    </ul>
                </li>-->
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-tasks"></span>
                        <span class="hidden-minibar">脑暴管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('HomepageManager/focusManager');?>" target="main">脑暴控制</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Task/taskList');?>" target="main">脑暴列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Task/addTask');?>" target="main">发布脑暴</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Task/fafangMoney');?>" target="main">脑暴奖金审核发放</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Idea/ideaList');?>" target="main">脑暴Idea管理</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Report/reportList');?>" target="main">Idea举报审核</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-user"></span>
                        <span class="hidden-minibar">用户管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Member/memList');?>" target="main">用户列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Member/addMem');?>" target="main">添加用户</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-user"></span>
                        <span class="hidden-minibar">作品管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Work/WorkList');?>" target="main">作品列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Work/workAdmin');?>" target="main">后台作品</a>
                        </li>
						<li>
                            <a href="<?php echo U('Work/addWork');?>" target="main">发布作品</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Work/checkWorkList');?>" target="main">待审核作品</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-envelope"></span>
                        <span class="hidden-minibar">消息管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>

                        <li>
                            <a href="<?php echo U('Letter/sendSms');?>" target="main">发送短信</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Letter/addLetter');?>" target="main">发送站内信</a>
                        </li>

                        <li>
                            <a href="<?php echo U('Letter/messageList');?>" target="main">站内信列表</a>
                        </li>

                        <li>
                            <a href="<?php echo U('Contact/messageList');?>" target="main">未读消息</a>
                        </li>

                        <li>
                            <a href="<?php echo U('Letter/sendEmail');?>" target="main">发送邮件</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-book"></span>
                        <span class="hidden-minibar">文章管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Article/articleList');?>" target="main">文章列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('ArticleCate/categoryList');?>" target="main">文章分类</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-cog"></span>
                        <span class="hidden-minibar">系统设置</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Config/config');?>" target="main">网站设置</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Advert/advertList');?>" target="main">焦点图设置</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-user"></span>
                        <span class="hidden-minibar">管理员</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Admin/adminList');?>" target="main">管理员列表</a>    
                        </li>
                        <li>
                            <a href="<?php echo U('AdminGroup/groupList');?>" target="main">管理员分组</a>    
                        </li>
                        <li>
                            <a href="<?php echo U('Admin/editPass');?>" target="main">修改密码</a>    
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-book"></span>
                        <span class="hidden-minibar">邀请码管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Invitation/invitationList');?>" target="main">邀请码列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Invitation/addInvitation');?>" target="main">创建邀请码</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-map-marker"></span>
                        <span class="hidden-minibar">海外区域管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Region/regionList');?>" target="main">区域信息列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Region/addRegion');?>" target="main">添加区域信息</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="nav-top-item">
                        <span class="icon glyphicon glyphicon-plus"></span>
                        <span class="hidden-minibar">产品管理</span>
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo U('Product/productlist');?>" target="main">产品列表</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Product/productadd');?>" target="main">添加产品</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>