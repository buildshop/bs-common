
var grid = {};
grid = {
    editcolums: function (gid, m, mod) {
        var t = this;
        $('body').append($('<div/>', {
            'id': 'dialog_edit_columns'
        }));

        var DIALOG_ID = '#dialog_edit_columns';
        $(DIALOG_ID).dialog({
            dialogClass: "dialog_edit_columns",
            modal: true,
            autoOpen: true,
            width: 500,
            height: 500,
            title: 'Доспутные ячейки',
            resizable: false,
            create: function (event, ui) {
            },
            open: function () {
                var that = this;
                //$('.ui-dialog-buttonset').addClass('btn-group');
                app.ajax('/admin/core/ajax/widget.editGridColumns', {
                    token: app.token,
                    grid_id: gid,
                    module: mod,
                    model: m
                }, function (data, textStatus, xhr) {
                    $(that).html(data);
                    // $(that).dialog({
                    //     position: "center"
                    //});
                    //  init_uniform();
                }, 'html', 'POST');
            },
            close: function () {
                $(this).remove();
            },
            buttons: [{
                    'text': app.message.save,
                    "class": 'btn btn-success',
                    'click': function () {
                        t.save(gid);
                    }
                },
                {
                    'text': app.message.cancel,
                    "class": 'btn btn-default',
                    'click': function () {
                        $(DIALOG_ID).remove();
                    }
                }]
        });
    },
    save: function (gridid) {
        var form = $('#edit_grid_columns_form').serialize();
        app.ajax('/admin/core/ajax/widget.editGridColumns', form, function () {
            $('#dialog_edit_columns').remove();
            console.log(gridid);
            //    window.location.reload("true");
            // $('#'+gridid).yiiGridView.update(gridid);
            $.fn.yiiGridView.update(gridid);//,{url: window.location.href}
            //   $.fn.yiiGridView({'ajaxUpdate':['shopmanufacturer-grid']});
        }, 'html', 'POST');

    }
};