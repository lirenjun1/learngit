$(function(){
    //tab切换
    tab($('#tabs li'),$('#tabs-content .ul'),'active');

    //分类是否允许操作
    $("select[name='cate_id']").change(function(){
        var cate_id = $(this).val();
        ajaxJudge('{:U("Goods/ajaxJudge")}',cate_id,9999,this);
    });

    //获取属性
    $("#type_id").change(function(){
        var goodsId = 0;
        if(goods_id != 0){
            goodsId = goods_id;
        }
        var type_id = $(this).val();
        if($(this).val() == 0){
            $('#create_attr').html('');
        }else{
            $.ajax({
                url:ajaxGoodsAttr,
                type:'GET',
                dataType:'json',
                data:{goodsId:goodsId,type_id:type_id},
                success:function(data){
                    $('#create_attr').html(data);
                }
            });
        }
    });

    //文本框编辑器
    KindEditor.ready(function(K){
        window.editor = K.create('#goods_desc',{
            items:[
                'source', '|', 'undo', 'redo', '|','indent','outdent', 'cut', 'copy','|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'clearhtml', 'selectall', '|', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor',
                'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough','image','link'
            ],
            afterBlur:function(){this.sync();}
        });
    });
});
//增加一个节点
function addSpec(obj){
    var html = '<div class="form-group">';
        html+='<label for="market_price" class="col-sm-3 control-label">'+obj.parentNode.parentNode.firstChild.innerHTML+'</label>';
        html+='<div class="col-sm-9">';
        html+= obj.parentNode.innerHTML.replace(/(.*)(addSpec)(.*)(\[)(\+)/i, "$1removeSpec$3$4-");
        html+='</div></div>';
    $('#create_attr').append(html);
}
//删除一个节点
function removeSpec(obj){
    var parentElement = obj.parentNode.parentNode;
    var id = document.getElementById('create_attr');
    if(parentElement){
        id.removeChild(parentElement);
    }
}
