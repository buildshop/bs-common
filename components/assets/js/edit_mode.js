function tinymce_ajax(obj){
    var str = $('#edit_mode').serialize();
    str+='&edit_mode=1&redirect=0';
    $.ajax({
        type:$('#edit_mode').attr('method'),
        url:$('#edit_mode').attr('action'),
        data:str,
        dataType:'json',
        beforeSend:function(){
            obj.setProgressState(true);
        },
        success: function(response){
            $.jGrowl(response.message);
            if(!response.valid){
                $.each(response.errors, function (key, data) {
                    console.log(key+': '+data);
                    $.jGrowl(data);
                });
            }
            obj.setProgressState(false);
        }
    });
}

tinymce.init({
    selector: ".edit_mode_title",
    language : "ru",
    inline: true,
    width : 100,
    plugins: "save",
    toolbar: "save undo redo",
    menubar: false,
    toolbar_items_size: 'small',
    save_enablewhendirty: true,
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    }
});

tinymce.init({
    selector: ".edit_mode_text",
    language : "ru",
    inline: true,
    width : 200,
    plugins: "save",
    toolbar: "save undo redo | styleselect",
    menubar: false,
    toolbar_items_size: 'small',
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    }
});