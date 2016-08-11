<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING'  =>array(

        '__HCSS__' =>'/happy/Application/Public/Home/css', // 更改默认的/Public 替换规则
        '__HJS__'     => '/happy/Application/Public/Home/js', // 增加新的JS类库路径替换规则
        '__HIMG__' =>  '/happy/Application/Public/Home/img', // 增加新的上传路径替换规则
        '__ACSS__' =>  '/happy/Application/Public/Admin/css', // 更改默认的/Public 替换规则
        '__AJS__'     =>  '/happy/Application/Public/Admin/js', // 增加新的JS类库路径替换规则
        '__AIMG__' => '/happy/Application/Public/Admin/image', // 增加新的上传路径替换规则
        '__UPL__' => '/happy/Application/Public/',//上传目录
    ),
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'happy', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'sc_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

);