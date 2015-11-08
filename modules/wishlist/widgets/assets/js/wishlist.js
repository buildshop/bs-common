var wishlist = window.wishlist || {};
wishlist = {
    add:function(product_id){
        app.ajax('/wishlist/add/'+product_id,{},function(data, textStatus, xhr){
            $('#countWishlist').html(data.count);
            app.report(data.message)
        },'json','GET');
    }
}


