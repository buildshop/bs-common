
/**
 * Function executed after product added to cart

function processCartResponse(text, data, textStatus, jqXHR)
{
    var productErrors = $('#productErrors');
    if(data.errors)
    {
        // Display errors
        productErrors.html(data.errors.join('<br/>')).show();
    }else{
        // Display "Successful message"
        productErrors.hide();
        reloadSmallCart();
        $.jGrowl(text, {position:"bottom-right"});
    }
} */