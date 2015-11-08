function rating(id){
    var url = '/shop/ajax/rating/'+id;
    var rating = $('input[name=rating_'+id+']:checked').val();
    $('input[name=rating_'+id+']').rating('disable');
    $.ajax({
        url: url,
        data:{rating:rating}
    });
    /*$.ajax({
        
    });*/
}