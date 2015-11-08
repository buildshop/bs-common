


// Process checked categories
$("#ShopProduct").submit(function(){
    var checked = $("#ShopCategoryTree li.jstree-checked");
    checked.each(function(i, el){
        var cleanId = $(el).attr("id").replace('ShopCategoryTreeNode_', '');
        $("#ShopProduct").append('<input type="hidden" name="categories[]" value="' + cleanId + '" />');
    });
});

$('#ShopCategoryTree').delegate("a", "click", function (event) {
    $('#ShopCategoryTree').jstree('checkbox').check_node($(this));
    var id = $(this).parent("li").attr('id').replace('ShopCategoryTreeNode_', '');
});

// Check node
;
(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('checkbox').check_node('#ShopCategoryTreeNode_' + id);
        });
    };
})(jQuery);

// On change `use configurations` select - load available attributes
$('#ShopProduct_use_configurations, #ShopProduct_type_id').change(function(){
    var attrs_block = $('#availableAttributes');
    var type_id = $('#ShopProduct_type_id').val();
    attrs_block.html('');

    if($('#ShopProduct_use_configurations').val() == '0') return;

    $.getJSON('/admin/shop/products/loadConfigurableOptions/?type_id='+type_id, function(data){
        var items = [];

        $.each(data, function(key, option) {
            items.push('<li><label><input type="checkbox" class="check" name="ShopProduct[configurable_attributes][]" value="' + option.id + '" name=""> ' + option.title + '</label></li>');
        });

        $('<ul/>', {
            'class': 'list-unstyled',
            html: items.join('')
        }).appendTo(attrs_block);
    });
});