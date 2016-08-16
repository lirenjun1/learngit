<?php
return array(
	//'配置项'=>'配置值'
	/* 项目设定 */
    'APP_STATUS'            => 'debug', // 应用调试模式状态 调试模式开启后有效 默认为debug 可扩展 并自动加载对应的配置文件
    'APP_FILE_CASE'         => false, // 是否检查文件的大小写 对Windows平台有效
    'APP_AUTOLOAD_PATH'     => '@.AutoLoad',// 自动加载机制的自动搜索路径,注意搜索顺序
    'APP_TAGS_ON'           => true, // 系统标签扩展开关
    'APP_SUB_DOMAIN_DEPLOY' => false, // 是否开启子域名部署
    'APP_SUB_DOMAIN_RULES'  => array(), // 子域名部署规则
    'APP_SUB_DOMAIN_DENY'   => array(), //  子域名禁用列表
    'ACTION_SUFFIX'         =>  '', // 操作方法后缀
    'MODULE_ALLOW_LIST'     => array('Home','Admin'), //分组
    'DEFAULT_GROUP'         => 'Home', //默认分组
	
	
	/* 系统变量名称设置 */
    'VAR_GROUP'             => 'g', // 默认分组获取变量
    'VAR_MODULE'            => 'm', // 默认模块获取变量
    'VAR_ACTION'            => 'a', // 默认操作获取变量
    'VAR_AJAX_SUBMIT'       => 'ajax', // 默认的AJAX提交变量
    'VAR_JSONP_HANDLER'     => 'callback',
    'VAR_PATHINFO'          => 's',	// PATHINFO 兼容模式获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    'VAR_URL_PARAMS'        => '_URL_', // PATHINFO URL参数变量
    'VAR_TEMPLATE'          => 't', // 默认模板切换变量
    'VAR_FILTERS'           =>  'filter_exp', // 全局系统变量的默认过滤方法 多个用逗号分割
	
	
    'TMPL_PARSE_STRING'  =>array(

        '__HCSS__' =>'/leyoou/Application/Public/Home/css', // 更改默认的/Public 替换规则
        '__HJS__'     => '/leyoou/Application/Public/Home/js', // 增加新的JS类库路径替换规则
        '__HIMG__' =>  '/leyoou/Application/Public/Home/img', // 增加新的上传路径替换规则
        '__ACSS__' =>  '/leyoou/Application/Public/Admin/css', // 更改默认的/Public 替换规则
        '__AJS__'     =>  '/leyoou/Application/Public/Admin/js', // 增加新的JS类库路径替换规则
        '__AIMG__' => '/leyoou/Application/Public/Admin/image', // 增加新的上传路径替换规则
        '__UPL__' => '/leyoou/Application/Public/',//上传目录
    ),
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'lhl', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'lhl_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	
	//定义模板变量
    'TMPL_PARSE_STRING'     => array(
        '__WEBROOT__'       => __ROOT__,
        '__WEBPUBLIC__'     => __ROOT__.'/Public',
        '__WEBUPL__'     => __ROOT__.'/Application/Public',
    ),

);