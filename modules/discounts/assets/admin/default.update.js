// Process checked categories
$("#ShopDiscount").submit(function(){
    var checked = $("#ShopDiscountCategoryTree li.jstree-checked");
    checked.each(function(i, el){
        var cleanId = $(el).attr("id").replace('ShopDiscountCategoryTreeNode_', '');
        $("#ShopDiscount").append('<input type="hidden" name="ShopDiscount[categories][]" value="' + cleanId + '" />');
    });
});

// Check node
;(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('checkbox').check_node('#ShopDiscountCategoryTreeNode_' + id);
        });
    };
})(jQuery);