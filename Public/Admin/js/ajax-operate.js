//异步登陆
function ajaxLogin(redirect,chart){
    var account  = $("input[name='account']").val(),
        password = $("input[name='password']").val(),
        verify   = $("input[name='verify']").val();
    if(account == ''){
        $('.notification').html('请输入用账号！！');
        $("input[name='account']").focus();return;
    }if(password == ''){
        $('.notification').html('请输入密码！！');
        $("input[name='password']").focus();return;
    }if(verify == ''){
        $('.notification').html('请输入验证码！！');
        $("input[name='verify']").focus();return;
    }

    $('.submit-btn').val('登录中...');
    $('.submit-btn').attr('disabled',true);
    $.ajax({
        url :redirect, type:'post', data:{account:account,password:password,verify:verify}, dataType:'JSON',
        success:function(data){
            if(data.error != null){
                $('.notification').html(data.error);
                
               var str=chart;
                // str=str.substring(0,40);
                

                
                str = str.split('.html');

                document.getElementById('verify_img').src = str[0] + '/' + new Date().getTime();
                $('.submit-btn').val('立刻登录');
                $('.submit-btn').attr('disabled',false);
            }else{
                 window.location.href = $('.form').attr('action');
            }
        }
    });
}

/**
 * 显示提示框
 */
function showNotification(type,txt){
    if(type == 'error'){
        $('.n-'+type).find('div').html(txt);
        $('.n-'+type).slideDown(400);
    }else{
        $('.'+type).find('div').html(txt);
        $('.'+type).slideDown(400);
    }
    setTimeout(function(){
        $('.notification').slideUp(400);
    },2500);
}

//判断分类 是否允许操作
function ajaxJudge(redirect,cate_id,self_id,obj){
    $.ajax({
        url: redirect,type: 'post',data: {cate_id:cate_id,self_id:self_id},dataType: 'JSON',
        success: function (data) {
            if(data.error != null){
                showNotification('error',data.error);
                $(obj).find('option:first').attr('selected', 'selected').parent('select');
            }
        }
    });
}

//是否显示操作
function  ajaxIsShow(redirect,id,flag,obj){
    $.ajax({
        url: redirect,type: 'post',data: {id:id,flag:flag},dataType: 'JSON',
        success: function (data) {
            if(data.error != null){
                showNotification('error',data.error);
            }else{
                $(obj).parent().find('.is-show-operate').show();
                $(obj).hide();
            }
        }
    });
}

//修改排序
function ajaxEditSort(redirect,id,sort){
    $.ajax({
        url: redirect,type: 'post',data: {id:id,sort:sort},dataType: 'JSON',
        success: function (data) {
            if(data.error != null){
                showNotification('error',data.error);
            }else{
                showNotification('success',data.success);
            }
        }
    });
}