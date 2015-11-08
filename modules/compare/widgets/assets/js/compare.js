var compare = window.compare || {};
compare = {
    add:function(product_id){
        app.ajax('/compare/add/'+product_id,{},function(data){
            $('#countCompare').html(data.count);
            app.report(data.message)
        },'json','GET');
    }
}


