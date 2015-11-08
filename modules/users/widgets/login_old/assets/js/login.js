function ajaxButtonSubmit(){
    $.ajax({
        url     : '/users/login',
        type    : 'POST',
        data    : $('#login-form').find('form').serialize(),
        success : function(result){
            $('#login-form').html(result);
        },
        error   : function(result){
        //alert(JSON.stringify(result));
        $('#login-form').html(result);
        }
    });
}

$(function(){
    $('#login-form').keydown(function(event){ 
        if (event.which == 13) {
           // event.preventDefault();
            ajaxButtonSubmit();
        }
    });

    $('body').append($('<div/>',{'id':'login-form'}));

});