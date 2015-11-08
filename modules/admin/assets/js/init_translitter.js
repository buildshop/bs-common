function init_translitter(model,isnew,xhr){
    var xhr;
    if(translate_object_url==0){
        $('#title').keyup(function(event){
            var title = $(this).val();
            if(xhr){

                $('#alias').val(ru2en.translit(title)).addClass('loading'); 

            }else{
                $('#alias').val(ru2en.translit(title)); 
            
            }

            if ($("#alias").val().length > 2) {
                $("#alias_result").hide();
                if(xhr){
                    if(typeof xhr !== 'undefined')
                        xhr.abort();
                    xhr = $.ajax({
                        url: '/admin/core/ajax/checkalias/',
                        type: 'POST',
                        data: {
                            model: model, 
                            alias: $("#alias").val(),
                            isNew: isnew
                        },
                        success: function(data) {
                            $("#alias").removeClass("loading");
                            if (data == 'true') {
                                $.jGrowl('URL занят',{
                                    position:'top-right'
                                });
                            } else {
                                $.jGrowl('URL свободен',{
                                    position:'top-right'
                                });                      
                            }
                        }
                    });
                }
            }
        });
    }
}

/*
function onlineTranslite(to, text){
    var lang = lang_name+'-'+to;
    $.ajax({
        url: "https://translate.yandex.net/api/v1.5/tr.json/translate",
        dataType: "json",
        async: false,
        data: {
            key: yandex_translate_apikey,
            format: "json",
            text:text,
            lang:lang
        },
        success: function(response) {
            $('#result').val(response.text);
            var res = response.text;
        }
    });
    return res;
}
*/


var onlineTranslite = function(to,text) {
    // var returned;
    var lang = lang_name+'-'+to;
    //this.getFormula = function(name) {
    return $.ajax({
        url: 'https://translate.yandex.net/api/v1.5/tr.json/translate',
        type:'POST',
        data: {
            key: yandex_translate_apikey,
            format: "json",
            text:text,
            lang:lang
        },
        dataType: 'json',
        async: false
    }).responseText;
//return returned.text;
//}
}
