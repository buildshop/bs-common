
// On form submit select all options
$("#ShopProductTypeForm").submit(function(){
    $("#box1View option").attr('selected', 'selected');
});

// Connect lists
$("#box2View").delegate('option', 'click', function(){
    var clon = $(this).clone();
    $(this).remove();
    $(clon).appendTo($("#box1View"));
});
$("#box1View").delegate('option', 'click', function(){
    var clon = $(this).clone();
    $(this).remove();
    $(clon).appendTo($("#box2View"));
});

// Process checked categories
$("#ShopProductTypeForm").submit(function(){
    var checked = $("#ShopTypeCategoryTree li.jstree-checked");
    checked.each(function(i, el){
        var cleanId = $(el).attr("id").replace('ShopTypeCategoryTreeNode_', '');
        $("#ShopProductTypeForm").append('<input type="hidden" name="categories[]" value="' + cleanId + '" />');
    });
});

// Process main category
$('#ShopTypeCategoryTree').delegate("a", "click", function (event) {
    $('#ShopTypeCategoryTree').jstree('checkbox').check_node($(this));
    var id = $(this).parent("li").attr('id').replace('ShopTypeCategoryTreeNode_', '');
    $('#main_category').val(id);
});

// Check node
;(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('checkbox').check_node('#ShopTypeCategoryTreeNode_' + id);
        });
    };
})(jQuery);
