function callbackSend(){
    var form = $("#callback-form");
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data:form.serialize(),
        success:function(data){
            $('#callback-dialog').html(data);
            $('.ui-widget-button').attr('disabled',false);

        },
        beforeSend:function(){
            $.jGrowl('loading...');
            $('.ui-widget-button').attr('disabled',true);
        },
        error: function(data) {
            $.jGrowl('Ошибка.'); 
        },

        dataType:'html'
    });

}