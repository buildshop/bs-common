function login(){
    var btn = $('#user-login-form .btn');
    $.ajax({
        url     : '/users/login',
        type    : 'POST',
        data    : $('#login-form').find('form').serialize(),
        success : function(result){
            $('#login-form').html(result);
            btn.attr('disabled',false);
        },
        error   : function(result){
            $('#login-form').html(result);
        },
        beforeSend:function(){
            btn.attr('disabled',true);
        }
    });
}

$(function(){
    $('#login-form form').keydown(function(event){ 
        if (event.which == 13) {
            // event.preventDefault();
            login();
        }
    });

    $('body').append($('<div/>',{
        'id':'login-form'
    }));

});