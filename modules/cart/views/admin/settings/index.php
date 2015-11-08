
<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>

<script>
function open_manual(){
    app.ajax('/admin/cart/settings/manual', {}, function(data){
        $('#content_manual_block').toggleClass('hidden');
    
    $('#content_manual').html(data)
    });
}
</script>

<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Документация',
    'htmlOptions' => array('class' => 'fluid hidden','id'=>'content_manual_block')
));
?>
<div id="content_manual"></div>
<?php
Yii::app()->tpl->closeWidget();
if ($this->isAjax) echo Html::closeTag('div');
?>