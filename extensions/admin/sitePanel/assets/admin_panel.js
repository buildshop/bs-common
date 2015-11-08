$(function(){
    
    var cook = 'visible';
    var cookName = 'admin-panel';
    var nameClass = 'hidden';
    
    if($.cookie(cookName)!=null){
        if($.cookie(cookName)==nameClass){
            $('#admin-panel').addClass(nameClass);
        }else{
            $('#admin-panel').removeClass(nameClass);
        }
    }
    $('#panel').click(function(){
        if($('#admin-panel').hasClass(nameClass)){
            cook = 'visible';
        }else{
            cook = 'hidden';
        }
        $('#admin-panel').toggleClass(nameClass);
        $.cookie(cookName,cook,{
            expires:30,
            path: '/'
        });
    })
});



