<?php
$this->widget('ext.jstree.FileBrowser');
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
?>

<script>
    $(function () {
        $(window).resize(function () {
            var h = Math.max($(window).height() - 0, 420);
            $('#data, #tree, #data .contentTree').height(h).filter('.default').css('lineHeight', h + 'px');
        }).resize();

        $('#tree').jstree({
            'core' : {
                'data' : {
                    'url' : '/admin/core/template/operation/?operation=get_node',
                    'data' : function (node) {
                        return { 'id' : node.id };
                    }
                },
                'check_callback' : function(o, n, p, i, m) {
                    if(m && m.dnd && m.pos !== 'i') { return false; }
                    if(o === "move_node" || o === "copy_node") {
                        if(this.get_node(n).parent === this.get_node(p).id) { return false; }
                    }
                    return true;
                },
                'themes' : {
                    'responsive' : false,
                    'variant' : 'small',
                    'stripes' : true
                }
            },
            'sort' : function(a, b) {
                return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
            },
            'contextmenu' : {
                'items' : function(node) {
                    var tmp = $.jstree.defaults.contextmenu.items();
                    delete tmp.create.action;
                    tmp.create.label = "New";
                    tmp.create.submenu = {
                        "create_folder" : {
                            "separator_after"	: true,
                            "label": "Папка",
                            "action": function (data) {
                                var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                                inst.create_node(obj, { type : "default" }, "last", function (new_node) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                });
                            }
                        },
                        "create_file" : {
                            "label": "Файл",
                            "action": function (data) {
                                var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                                inst.create_node(obj, { type : "file" }, "last", function (new_node) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                });
                            }
                        }
                    };
                    if(this.get_type(node) === "file") {
                        delete tmp.create;
                    }
                    return tmp;
                }
            },
            'types' : {
                'default' : { 'icon' : 'folder' },
                'file' : { 'valid_children' : [], 'icon' : 'file' }
            },
            'unique' : {
                'duplicate' : function (name, counter) {
                    return name + ' ' + counter;
                }
            },
            'plugins' : ['state','dnd','sort','types','contextmenu','unique']
        })
        .on('delete_node.jstree', function (e, data) {
            $.get('/admin/core/template/operation/?operation=delete_node', { 'id' : data.node.id })
            .fail(function () {
                data.instance.refresh();
            });
        })
        .on('create_node.jstree', function (e, data) {
            $.get('/admin/core/template/operation/?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
            .done(function (d) {
                data.instance.set_id(data.node, d.id);
            })
            .fail(function () {
                data.instance.refresh();
            });
        })
        .on('rename_node.jstree', function (e, data) {
            $.get('/admin/core/template/operation/?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
            .done(function (d) {
                data.instance.set_id(data.node, d.id);
            })
            .fail(function () {
                data.instance.refresh();
            });
        })
        .on('move_node.jstree', function (e, data) {
            $.get('/admin/core/template/operation/?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent })
            .done(function (d) {
                //data.instance.load_node(data.parent);
                data.instance.refresh();
            })
            .fail(function () {
                data.instance.refresh();
            });
        })
        .on('copy_node.jstree', function (e, data) {
            $.get('/admin/core/template/operation/?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
            .done(function (d) {
                //data.instance.load_node(data.parent);
                data.instance.refresh();
            })
            .fail(function () {
                data.instance.refresh();
            });
        })
        .on('changed.jstree', function (e, data) {
            if(data && data.selected && data.selected.length) {
                $.get('/admin/core/template/operation/?operation=get_content&id=' + data.selected.join(':'), function (d) {
                    if(d && typeof d.type !== 'undefined') {
                        $('#filename').val(data.selected.join(':'));
                        $('#data .contentTree').hide();
                        switch(d.type) {
                            case 'text':
                            case 'txt':
                            case 'md':
                            case 'htaccess':
                            case 'log':
                            case 'sql':
                            case 'php':
                            case 'js':
                            case 'json':
                            case 'css':
                            case 'html':
                                $('#data .code').show();
                                $('#code').val(d.content).attr('readonly',d.readonly);
                                break;
                            case 'png':
                            case 'jpg':
                            case 'jpeg':
                            case 'bmp':
                            case 'gif':
                                $('#data .image img').one('load', function () { $(this).css({'marginTop':'-' + $(this).height()/2 + 'px','marginLeft':'-' + $(this).width()/2 + 'px'}); }).attr('src',d.content);
                                $('#data .image').show();
                                break;
                            default:
                                $('#data .default').html(d.content).show();
                                break;
                        }
                    }
                });
            }
            else {
                $('#data .contentTree').hide();
                $('#data .default').html('Select a file from the tree.').show();
            }
        });
    });
</script>



<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
?>
<form method="post" action="/admin/core/template/">
    <input type="hidden" name="file" id="filename" />
    <div id="tree"></div>
    <div id="data">
        <div class="contentTree code" style="display:none;">
            <textarea id="code" name="content"></textarea>
            <pre id="pre" style="display:none;"></pre>
        </div>
        <div class="contentTree folder" style="display:none;"></div>
        <div class="contentTree image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
        <div class="contentTree default" style="text-align:center;">Select a file from the tree.</div>
    </div>


    <div class="formRow buttons textC noBorderB">
        <input type="submit" value="Save" class="buttonS bGreen" />
    </div>
</form>
<?php Yii::app()->tpl->closeWidget();?>
<?php
if(Yii::app()->request->isAjaxRequest) echo Html::closeTag('div');
?>