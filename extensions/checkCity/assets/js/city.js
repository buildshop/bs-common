function setCity(t,city){     
    $.ajax({
        url:'/ajax/city.action',
        type:'POST',
        dataType:'json',
        data:{
            city:city
        },
        success:function(data){
            if($(t).attr('data-button-yes')){
                location.reload();  
            }
            $('#select-city').popover('hide');
            $('#header-phone').html(data.phone);
            $('#contaner-city').remove();
                    
            $('#current-city').html(data.name);
              
        }
    });
} 

function load_select_city(){
    $('body').append('<div id="contaner-city"></div>');
    $('#contaner-city').dialog({
        model:true,
        autoOpen: true,
        draggable:false,
        height: 'auto',
        title:'Выбрать город',
        closeOnEscape:false,
        width: 550,
        modal: true,
        resizable: false,
        open:function(){
            var that = this;
            $.ajax({
                url:'/ajax/city.action',
                type:'GET',
                success:function(data){
                    $(that).html(data);
                    $('.ui-dialog').position({
                        my: 'center',
                        at: 'center',
                        of: window,
                        collision: 'fit'
                    });
                }
            });
        },
        close:function(){
            $(this).remove();
            $('#select-city').popover('hide');

        }
    });

}    
