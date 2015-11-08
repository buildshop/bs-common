
$(function(){

    var xhr;
    setInterval(function(){
       // xhr.abort();
        reloadCounters();
    }, 5000);



    function reloadCounters() {
        xhr = $.getJSON('/admin/core/ajax/getCounters?' + Math.random(), function(data){

            if(data.orders > 0){
                $('#newOrderCount').html(data.orders).show();
            }else{
                $('#newOrderCount').hide();
            }
            if(data.notify > 0){
                $('#newNotifyCount').html(data.notify).show();
            }else{
                $('#newNotifyCount').hide();
            }
            if(data.comments > 0){
                $('#newCommentsCount').html(data.comments).show();
            }else{
                $('#newCommentsCount').hide();
            }

        });
    }

});